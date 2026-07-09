<?php
namespace App\Ship\Services;

use App\Containers\SharedSection\Room\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_KEY = 'app_settings';

    /**
     * Lấy giá trị setting theo key
     */
    public function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Cập nhật hoặc tạo mới setting
     */
    public function set(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Xóa cache (khi cần)
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
