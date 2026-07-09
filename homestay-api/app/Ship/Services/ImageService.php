<?php

namespace App\Ship\Services;

use App\Containers\SharedSection\Room\Models\Media;
use App\Containers\SharedSection\Room\Models\RoomHighlightAmenityImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Throwable;


class ImageService
{
    protected ImageManager $im;

    public function __construct()
    {
        // Ưu tiên Imagick nếu có
        $this->im = new ImageManager(
            extension_loaded('imagick') ? new ImagickDriver() : new GdDriver()
        );
    }

    /** Upload ảnh gốc + sinh variants (nhiều size & nhiều định dạng). */
    public function upload(UploadedFile $file, ?string $dir = null, string $disk = 's3', $data)
    {
        $dir ??= 'uploads/' . now()->format('Y/m');

        $path = $file->store($dir, $disk);

        [$w, $h] = getimagesize($file->getRealPath());
        $modelClass = !empty($data['highlight_amenity_id'])
            ? RoomHighlightAmenityImage::class
            : Media::class;
        $commonData = [
            'disk'   => $disk,
            'path'   => $path,
            'width'  => $w,
            'height' => $h,
            'mime'   => $file->getMimeType(),
        ];
        if (!empty($data['highlight_amenity_id'])) {
            $extraData = [
                'highlight_amenity_id' => $data['highlight_amenity_id'],
            ];
        } else {
            $extraData = [
                'room_id' => $data['room_id'] ?? null,
                'room_image_area_group_id' => $data['room_image_area_group_id'] ?? null,
                'sort_index' => $data['index'],
            ];
        }
        $media = $modelClass::create(array_merge($commonData, $extraData));
        $variants = $this->generateVariants($media);
        $media->update(['variants' => $variants]);
        return $media;
    }

    /** Sinh biến thể theo config/image-sizes + định dạng (webp/jpg/avif). */
    public function generateVariants($media): array
    {
        $sizes = config('image-sizes', []);
        $formats = config('image.formats', ['webp', 'jpg']);
        $quality = config('image.quality', ['jpg' => 100, 'webp' => 100, 'jpeg' => 100, 'png' => 100]);

        $disk = Storage::disk($media->disk);
        $origAbs = $disk->url($media->path);
        $content = file_get_contents($origAbs); // tải về
        $img = $this->im->read($content)->orient(); // sửa hướng EXIF
        $pathInfo = pathinfo($media->path);
        $base = $pathInfo['dirname'] . '/' . $pathInfo['filename'];


        $variants = [];

        foreach ($sizes as $name => [$w, $h, $crop]) {
            $canvas = clone $img;

            if ($crop && $w && $h) {
                $canvas = $canvas->cover($w, $h); // crop trung tâm
            } else {
                if ($w && !$h) $canvas = $canvas->scaleDown($w);
                elseif (!$w && $h) $canvas = $canvas->scaleDown(height: $h);
                else                 $canvas = $canvas->scaleDown($w ?? $img->width(), $h ?? $img->height());
            }

            $entry = [
                'width' => $canvas->width(),
                'height' => $canvas->height(),
            ];

            foreach ($formats as $fmt) {
                try {
                    if ($fmt === 'webp') {
                        $bin = (string)$canvas->toWebp($quality['webp'] ?? 100);
                        $path = $base . '-' . $name . '.webp';
                        $mime = 'image/webp';
                    } elseif ($fmt === 'avif') {
                        $bin = (string)$canvas->toAvif($quality['avif'] ?? 100);
                        $path = $base . '-' . $name . '.avif';
                        $mime = 'image/avif';
                    } else { // jpg
                        $bin = (string)$canvas->toJpeg();
                        $path = $base . '-' . $name . '.jpg';
                        $mime = 'image/jpeg';
                    }
                    $disk->put($path, $bin);
                    $entry[$fmt] = [
                        'path' => $path,
                        'bytes' => strlen($bin),
                        'mime' => $mime,
                    ];
                } catch (Throwable $e) {
                    // Bỏ qua định dạng không hỗ trợ (vd AVIF trên GD)
                }
            }

            $variants[$name] = $entry;
        }

        return $variants;
    }

    /** URL theo size + format (ưu tiên format đầu trong config). */
    public function url($m, ?string $size = null, ?string $format = null): string
    {
        $formats = config('image.formats', ['webp', 'jpg']);

        if ($size && isset($m->variants[$size])) {
            $entry = $m->variants[$size];
            $fmt = $format ?: $this->pickFirstAvailableFormat($entry, $formats);
            if ($fmt && isset($entry[$fmt]['path'])) {
                return Storage::disk($m->disk)->url($entry[$fmt]['path']);
            }
        }
        return $m->url();
    }

    protected function pickFirstAvailableFormat(array $entry, array $pref): ?string
    {
        foreach ($pref as $f) if (isset($entry[$f])) return $f;
        foreach ($entry as $k => $v) if (is_array($v) && isset($v['path'])) return $k;
        return null;
    }

    /** Build srcset cho 1 format (width descriptor: 360w, 480w…). */
//    public function srcset($m, string $format = 'jpg'): ?string
//    {
//        if (!$m->variants) return null;
//
//        $c = collect($m->variants)
//            ->filter(fn($v) => isset($v[$format]))
//            ->map(fn($v) => [
//                'w' => $v['width'],
//                'url' => Storage::disk($m->disk)->url($v[$format]['path']),
//            ])
//            ->sortBy('w')
//            ->values();
//
//        return $c->isEmpty()
//            ? null
//            : $c->map(fn($x) => "{$x['url']} {$x['w']}w")->implode(', ');
//    }
    public function srcset($disk, $m, string $format = 'jpg'): ?string
    {
        if (empty($m->variants)) return null;

        $srcset = [];
        foreach ($m->variants as $v) {
            if (!isset($v[$format])) continue;

            // nếu $v[$format]['path'] là path đầy đủ, dùng URL base
            $url = $disk->url($v[$format]['path']);
            $srcset[] = "{$url} {$v['width']}w";
        }

        if (empty($srcset)) return null;

        // sort theo width
        usort($srcset, function ($a, $b) {
            preg_match('/(\d+)w$/', $a, $ma);
            preg_match('/(\d+)w$/', $b, $mb);
            return $ma[1] <=> $mb[1];
        });

        return implode(', ', $srcset);
    }

    /** Chọn bản gần nhất theo targetWidth (để set width/height & fallback src). */
    public function pickNearest($m, int $targetWidth): array
    {
        // Normalize variants: handle JSON string, objects, nulls
        $raw = $m->variants ?? [];
        if (is_string($raw)) {
            $variants = json_decode($raw, true) ?: [];
        } elseif (is_object($raw) && method_exists($raw, 'toArray')) {
            $variants = $raw->toArray();
        } else {
            $variants = (array)$raw;
        }

        $cand = collect($variants)->map(function ($v) {
            // decode if this item itself is JSON string
            if (is_string($v)) {
                $decoded = json_decode($v, true);
                if (is_array($decoded)) $v = $decoded;
            }

            // width/height may be top-level or inside a nested format (jpg/webp...)
            $width = $v['width'] ?? null;
            $height = $v['height'] ?? null;

            if ($width === null || $height === null) {
                foreach ($v as $k => $maybe) {
                    if (is_array($maybe) && (isset($maybe['width']) || isset($maybe['path']))) {
                        $width = $width ?? ($maybe['width'] ?? null);
                        $height = $height ?? ($maybe['height'] ?? null);
                        break;
                    }
                }
            }

            $fmt = $this->pickFirstAvailableFormat($v, config('image.formats', ['webp', 'jpg']));

            return [
                'w' => $width ? (int)$width : null,
                'h' => $height ? (int)$height : null,
                'fmt' => $fmt,
                'v' => $v,
            ];
        })->filter(fn($x) => !empty($x['w']));

        // fallback nếu không có variant hợp lệ
        if ($cand->isEmpty()) {
            $w = $m->width ?? ($m['width'] ?? null);
            $h = $m->height ?? ($m['height'] ?? null);
            $url = null;

            if (is_object($m) && method_exists($m, 'url')) {
                $url = $m->url();
            } elseif (is_array($m) && isset($m['path'])) {
                $disk = $m['disk'] ?? config('filesystems.default');
                $url = Storage::disk($disk)->url($m['path']);
            } elseif (is_string($m)) {
                $url = $m; // nếu $m là đường dẫn string
            }

            return ['w' => $w, 'h' => $h, 'url' => $url];
        }

        $best = $cand->sortBy(fn($x) => abs($x['w'] - $targetWidth))->first();
        $bestV = $best['v'];
        $fmt = $best['fmt'];

        // Tìm path an toàn
        $path = null;
        if (is_array($bestV) && isset($bestV[$fmt]['path'])) {
            $path = $bestV[$fmt]['path'];
        } elseif (is_array($bestV) && isset($bestV['path'])) {
            $path = $bestV['path'];
        } elseif (is_string($bestV)) {
            $path = $bestV;
        } else {
            foreach ((array)$bestV as $maybe) {
                if (is_array($maybe) && isset($maybe['path'])) {
                    $path = $maybe['path'];
                    break;
                }
            }
        }

        $disk = $m->disk ?? ($m['disk'] ?? config('filesystems.default'));
        $url = $path ? Storage::disk($disk)->url($path) : null;

        return ['w' => $best['w'], 'h' => $best['h'], 'url' => $url];
    }


    protected function nearestSizeName($m, $width): ?string
    {
        if (!$m->variants) return null;

        return collect($m->variants)
            ->map(function ($v, $name) {
                $w = null;

                if (is_array($v)) {
                    // nếu mảng có key width thì lấy
                    if (isset($v['width'])) {
                        $w = $v['width'];
                    } // fallback: nếu mảng con (jpg/webp) thì lấy width trong đó nếu có
                    elseif (isset($v['jpg']['width'])) {
                        $w = $v['jpg']['width'];
                    }
                }

                return ['name' => $name, 'w' => $w];
            })
            ->filter(fn($x) => !empty($x['w'])) // chỉ giữ những cái có width
            ->sortBy(fn($x) => abs($x['w'] - $width))
            ->value('name');
    }

    /** Render payload JSON cho FE (React) để vẽ <picture>. */
    public function toResponsivePayload($m, string $sizes, string $targetSize = 'content-1440'): array
    {
        $formats = config('image.formats', ['webp', 'jpg']);
        $sources = [];
        $disk = Storage::disk($m->disk);
        foreach ($formats as $fmt) {
            if ($ss = $this->srcset($disk, $m, $fmt)) {
                $mime = $fmt === 'webp' ? 'image/webp'
                    : ($fmt === 'avif' ? 'image/avif' : 'image/jpeg');
                $sources[] = [
                    'type' => $mime,
                    'srcset' => $ss,
                    'sizes' => $sizes,
                ];
            }
        }

//        $targetW = data_get(config("image-sizes.$targetSize"), 0, 1440);
//        $nearest = $this->pickNearest($m, $targetW);
//        $jpegSrc = $this->url($m, $this->nearestSizeName($m, $nearest['w']), 'jpg');
        return [
            'id' => $m->id,
//            'width' => $nearest['w'],
//            'height' => $nearest['h'],
            'sizes' => $sizes,
            'sources' => $sources,
//            'img' => [
//                'src' => $jpegSrc,
//                'width' => $nearest['w'],
//                'height' => $nearest['h'],
//            ],
        ];
    }

    public function toMobilePayload($m, string $targetSize = 'content-1440'): array
    {
        $targetW = data_get(config("image-sizes.$targetSize"), 0, 1440);
        $nearest = $this->pickNearest($m, $targetW);

        // lấy url jpg cho kích thước mobile
        $jpegSrc = $this->url(
            $m,
            $this->nearestSizeName($m, $nearest['w']),
            'jpg'
        );
        return [
            'id' => $m->id,
            'width' => $nearest['w'],
            'height' => $nearest['h'],
            'img' => [
                'src' => $jpegSrc,
                'width' => $nearest['w'],
                'height' => $nearest['h'],
            ],
        ];
    }

    public function toAdminPayload($m, string $targetSize = 'content-480'): array
    {
        $targetW = data_get(config("image-sizes.$targetSize"), 0, 480);
        $nearest = $this->pickNearest($m, $targetW);

        $jpegSrc = $this->url(
            $m,
            $this->nearestSizeName($m, $nearest['w']),
            'jpg'
        );
        return [
            'id' => $m->id,
            'width' => $nearest['w'],
            'height' => $nearest['h'],
            'img' => [
                'src' => $jpegSrc,
                'width' => $nearest['w'],
                'height' => $nearest['h'],
            ],
        ];
    }

    /** Xoá file & record. */
    public function delete(Media $m): void
    {
        $disk = Storage::disk($m->disk);
        foreach (($m->variants ?? []) as $v) {
            foreach ($v as $fmt => $data) {
                if (is_array($data) && isset($data['path'])) $disk->delete($data['path']);
            }
        }
        $disk->delete($m->path);
        $m->delete();
    }
}
