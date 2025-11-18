<?php
namespace App\Helpers;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UploadDataToCloudinary
{
    protected static function cloudinary(): Cloudinary
    {
        return new Cloudinary(config('services.cloudinary'));
    }

    // Subir imagen, devuelve ['public_id', 'secure_url']
    public static function uploadImage(string $id, UploadedFile $image, string $folder): array
    {
        try {
            $cloudinary = self::cloudinary();

            $result = $cloudinary->uploadApi()->upload($image->getRealPath(), [
                'folder' => $folder,
                'public_id' => 'soapamz-' . $id,
                'overwrite' => true,
                'resource_type' => 'image',
                'type' => 'upload',
                'transformation' => [
                    [
                        'width' => 800, 'height' => 800, 'crop' => 'crop',
                        'gravity' => 'center', 'quality' => 'auto:low', 'fetch_format' => 'auto'
                    ]
                ]
            ]);

            return [
                'public_id' => $result['public_id'] ?? null,
                'secure_url' => $result['secure_url'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Error subir imagen Cloudinary: ' . $e->getMessage());
            throw $e;
        }
    }

    // Subir documento/factura, devuelve ['public_id','secure_url']
    public static function uploadDocument(string $id, UploadedFile $document, string $folder): array
    {
        try {
            $cloudinary = self::cloudinary();

            $result = $cloudinary->uploadApi()->upload($document->getRealPath(), [
                'folder' => $folder,
                'public_id' => 'soapamz-' . $id,
                'overwrite' => true,
                'resource_type' => 'auto',
                'type' => 'authenticated',
            ]);

            return [
                'public_id' => $result['public_id'] ?? null,
                'secure_url' => $result['secure_url'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Error subir documento Cloudinary: ' . $e->getMessage());
            throw $e;
        }
    }

    // Eliminar archivo por public_id o URL
    public static function removeFile(string $urlOrPublicId): bool
    {
        try {
            $cloudinary = self::cloudinary();

            $publicId = self::looksLikeUrl($urlOrPublicId)
                ? self::getPublicIdFromUrl($urlOrPublicId)
                : $urlOrPublicId;

            // Detectar resource_type y type basado en el public_id
            if (strpos($publicId, 'invoices') !== false) {
                $resourceType = 'raw';
                $type = 'authenticated';
            } elseif (strpos($publicId, 'evidence') !== false) {
                $resourceType = 'image';
                $type = 'upload';
            } else {
                $resourceType = 'auto';
                $type = 'upload';
            }

            $result = $cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => $resourceType,
                'type' => $type,
            ]);

            Log::info('Cloudinary destroy', [
                'public_id' => $publicId,
                'resource_type' => $resourceType,
                'type' => $type,
                'result' => $result,
            ]);

            return isset($result['result']) && in_array($result['result'], ['ok', 'not_found']);
        } catch (\Throwable $e) {
            Log::error('Error eliminar Cloudinary: ' . $e->getMessage());
            return false;
        }
    }

    private static function looksLikeUrl($string): bool
    {
        return (bool) filter_var($string, FILTER_VALIDATE_URL);
    }

    // Extrae public_id robusto desde URL de Cloudinary
    public static function getPublicIdFromUrl($url)
    {
        if (!$url) return null;

        $url = preg_replace('/\?.*/', '', $url);
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) return null;

        if (strpos($url, 'authenticated') !== false) {
            $path = preg_replace('#^/(?:image|raw)/authenticated(?:/s--[A-Za-z0-9_-]+--)?/v\d+/?#', '', $path);
            $path = preg_replace('/\.[^.]+$/', '', $path);
        } else {
            $path = preg_replace('#^/(?:image|raw)/upload/v\d+/?#', '', $path);
            $path = preg_replace('/\.[^.]+$/', '', $path);
        }

        return trim($path, '/');
    }
}
