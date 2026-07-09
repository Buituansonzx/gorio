<?php

namespace App\Ship\Helpers;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3Helper
{
    protected S3Client $s3Client;
    protected string $bucket;
    protected string $region;
    protected string $kmsKeyId;

    public function __construct()
    {
        $this->bucket = config('filesystems.disks.s3.bucket');
        $this->region = config('filesystems.disks.s3.region');
        $this->kmsKeyId = config('filesystems.disks.s3.kms_key_id');

        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $this->region,
            'signature_version' => 'v4',
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);
    }

    /**
     * Upload file to S3
     */
    public function uploadFile(UploadedFile $file, string $directory = '', string $fileName = null): array
    {
        try {
            // Generate file name if not provided
            if (!$fileName) {
                $fileName = $this->generateFileName($file);
            }

            // Construct full path
            $fullPath = $directory ? trim($directory, '/') . '/' . $fileName : $fileName;

            // Upload parameters with SSE-KMS
            $uploadParams = [
                'Bucket' => $this->bucket,
                'Key' => $fullPath,
                'Body' => fopen($file->getPathname(), 'r'),
                'ContentType' => $file->getMimeType(),
            ];

            // Upload file
            $result = $this->s3Client->putObject($uploadParams);

            return [
                'success' => true,
                'file_path' => $fullPath,
                'file_url' => $this->getFileUrl($fullPath),
                'file_size' => $file->getSize(),
                'content_type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ];

        } catch (AwsException $e) {
            Log::error('S3 Upload Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles(array $files, string $directory = ''): array
    {
        $results = [];

        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $results[$index] = $this->uploadFile($file, $directory);
            } else {
                $results[$index] = [
                    'success' => false,
                    'error' => 'Invalid file at index ' . $index
                ];
            }
        }

        return $results;
    }

    /**
     * Delete file from S3
     */
    public function deleteFile(string $filePath): array
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $filePath,
            ]);

            return [
                'success' => true,
                'message' => 'File deleted successfully',
            ];

        } catch (AwsException $e) {
            Log::error('S3 Delete Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }

    /**
     * Delete multiple files
     */
    public function deleteMultipleFiles(array $filePaths): array
    {
        try {
            $objects = [];
            foreach ($filePaths as $filePath) {
                $objects[] = ['Key' => $filePath];
            }

            $result = $this->s3Client->deleteObjects([
                'Bucket' => $this->bucket,
                'Delete' => [
                    'Objects' => $objects,
                ],
            ]);

            return [
                'success' => true,
                'deleted' => $result['Deleted'] ?? [],
                'errors' => $result['Errors'] ?? [],
            ];

        } catch (AwsException $e) {
            Log::error('S3 Bulk Delete Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }

    /**
     * Get presigned URL for private files
     */
    public function getPresignedUrl(string $filePath, int $expiresInMinutes = 60): string
    {
        try {
            $cmd = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $filePath,
            ]);

            $request = $this->s3Client->createPresignedRequest(
                $cmd,
                '+' . $expiresInMinutes . ' minutes'
            );
            return (string) $request->getUri();

        } catch (AwsException $e) {
            Log::error('S3 Presigned URL Error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Check if file exists
     */
    public function fileExists(string $filePath): bool
    {
        try {
            $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $filePath,
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $filePath): array
    {
        try {
            $result = $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $filePath,
            ]);

            return [
                'success' => true,
                'file_path' => $filePath,
                'file_size' => $result['ContentLength'],
                'content_type' => $result['ContentType'],
                'last_modified' => $result['LastModified']->format('Y-m-d H:i:s'),
                'etag' => trim($result['ETag'], '"'),
                'server_side_encryption' => $result['ServerSideEncryption'] ?? null,
                'kms_key_id' => $result['SSEKMSKeyId'] ?? null,
                'metadata' => $result['Metadata'] ?? [],
            ];

        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }

    /**
     * Generate unique file name
     */
    protected function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);

        return $baseName . '_' . $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Get URL for file (presigned URL since bucket doesn't allow ACLs)
     */
    public function getFileUrl($filePath)
    {
        if(empty($filePath)){
            return null;
        }
        // Since bucket doesn't allow ACLs, return presigned URL with longer expiry
        return $this->getPresignedUrl($filePath, 1440); // 24 hours
    }

    /**
     * Copy file within S3
     */
    public function copyFile(string $sourcePath, string $destinationPath): array
    {
        try {
            $this->s3Client->copyObject([
                'Bucket' => $this->bucket,
                'CopySource' => $this->bucket . '/' . $sourcePath,
                'Key' => $destinationPath,
                'ServerSideEncryption' => 'aws:kms',
                'SSEKMSKeyId' => $this->kmsKeyId,
            ]);

            return [
                'success' => true,
                'source_path' => $sourcePath,
                'destination_path' => $destinationPath,
                'destination_url' => $this->getFileUrl($destinationPath),
            ];

        } catch (AwsException $e) {
            Log::error('S3 Copy Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }

    /**
     * Upload base64 image to S3
     */
    public function uploadBase64Image(string $base64Data, string $directory = '', string $fileName = null): array
    {
        try {
            // Decode base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
                $imageType = $matches[1];
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            } else {
                $imageType = 'jpg'; // default
            }

            $imageData = base64_decode($base64Data);

            if (!$imageData) {
                return [
                    'success' => false,
                    'error' => 'Invalid base64 image data'
                ];
            }

            // Generate file name if not provided
            if (!$fileName) {
                $timestamp = now()->format('YmdHis');
                $random = Str::random(8);
                $fileName = 'image_' . $timestamp . '_' . $random . '.' . $imageType;
            }

            // Construct full path
            $fullPath = $directory ? trim($directory, '/') . '/' . $fileName : $fileName;

            // Upload parameters with SSE-KMS
            $uploadParams = [
                'Bucket' => $this->bucket,
                'Key' => $fullPath,
                'Body' => $imageData,
                'ContentType' => 'image/' . $imageType,
                'ServerSideEncryption' => 'aws:kms',
                'SSEKMSKeyId' => $this->kmsKeyId,
                'Metadata' => [
                    'uploaded-at' => now()->toISOString(),
                    'source' => 'base64-upload',
                ]
            ];

            // Upload file
            $result = $this->s3Client->putObject($uploadParams);

            return [
                'success' => true,
                'file_path' => $fullPath,
                'file_url' => $this->getFileUrl($fullPath),
                'file_size' => strlen($imageData),
                'content_type' => 'image/' . $imageType,
                'etag' => trim($result['ETag'], '"'),
                'server_side_encryption' => $result['ServerSideEncryption'] ?? null,
                'kms_key_id' => $result['SSEKMSKeyId'] ?? null,
            ];

        } catch (AwsException $e) {
            Log::error('S3 Base64 Upload Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }
    public static function getS3ImageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null; // hoặc trả về 1 ảnh mặc định
        }

        return Storage::disk('s3')->url($path);
    }
    public function uploadRaw(string $content, string $path, string $mime): array
    {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $path,
                'Body' => $content,
                'ContentType' => $mime,
                'ServerSideEncryption' => 'aws:kms',
                'SSEKMSKeyId' => $this->kmsKeyId,
                'Metadata' => [
                    'uploaded-at' => now()->toISOString(),
                    'source' => 'raw-upload',
                ]
            ]);

            return [
                'success' => true,
                'file_path' => $path,
                'file_url' => $this->getFileUrl($path),
                'file_size' => strlen($content),
                'content_type' => $mime,
                'etag' => trim($result['ETag'], '"'),
                'server_side_encryption' => $result['ServerSideEncryption'] ?? null,
                'kms_key_id' => $result['SSEKMSKeyId'] ?? null,
            ];

        } catch (\Aws\Exception\AwsException $e) {
            \Log::error('S3 Raw Upload Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getAwsErrorCode(),
            ];
        }
    }
}
