<?php

namespace App\Helpers;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class UploadDataToCloudinary
{
    public static function uploadImage(string $id, UploadedFile $image, string $folder): string
    {
        try {
            $cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL']);
            $config = [
                'folder' => $folder,
                'public_id' => 'soapamz-' . $id,
                'overwrite' => true,
                'resource_type' => 'image',
                'type' => 'authenticated',
                'transformation' => [
                    [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'crop',
                        'gravity' => 'center',
                        'quality' => 'auto:low',
                        'fetch_format' => 'auto'
                    ]
                ]
            ];
            $result = $cloudinary->uploadApi()->upload($image->getRealPath(), $config);
            return $result['public_id'];
        } catch (\Throwable $th) {
            throw new \Exception('Error al subir la imagen: ' . $th->getMessage());
        }
    }

    public static function uploadVideo(string $id, UploadedFile $video, string $folder): string
    {
        try {
            $cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL']);
            $config = [
                'folder' => $folder,
                'public_id' => 'soapamz-' . $id,
                'overwrite' => true,
                'resource_type' => 'video',
                'type' => 'authenticated',
                'transformation' => [
                    [
                        'width' => 800,
                        'height' => 800,
                        'crop' => 'limit',
                        'quality' => 'auto:low',
                        'fetch_format' => 'auto'
                    ]
                ]
            ];
            $result = $cloudinary->uploadApi()->upload($video->getRealPath(), $config);
            return $result['public_id'];
        } catch (\Throwable $th) {
            throw new \Exception('Error al subir el video: ' . $th->getMessage());
        }
    }

    public static function uploadDocument(string $id, UploadedFile $document, string $folder): string
    {
        try {
            $cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL']);
            $config = [
                'folder' => $folder,
                'public_id' => 'soapamz-' . $id,
                'overwrite' => true,
                'resource_type' => 'auto',
                'type' => 'authenticated',
            ];
            $result = $cloudinary->uploadApi()->upload($document->getRealPath(), $config);
            return $result['public_id'];
        } catch (\Throwable $th) {
            throw new \Exception('Error al subir el documento: ' . $th->getMessage());
        }
    }

    public static function removeFile(string $publicId, string $resourceType = 'auto'): bool
    {
        try {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ]
            ]);

            $result = $cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => $resourceType,
                'type' => 'authenticated',
            ]);

            $succes = $result['result'] === 'ok';
            return $succes;
        } catch (\Throwable $e) {
            return false;
        }
    }


    public static function getSignedAuthenticatedUrl(string $publicId, string $resourceType = 'auto', ?string $format = null): ?string
    {

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true,
                'sign_url' => true,
            ],
        ]);

        $cleanPublicId = preg_replace('#\\\\/#', '/', $publicId);
        $expiresAt = time() + 3600;

        return $cloudinary->uploadApi()->privateDownloadUrl(
            $cleanPublicId,
            $format ?? 'jpg',
            [
                'resource_type' => $resourceType,
                'type' => 'authenticated',
                'expires_at' => $expiresAt,
            ]
        );
    }

    public static function getFirstPublicImageUrlByPrefix(string $folder, string $partialName, string $format = 'jpg'): ?string
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);

        $result = $cloudinary->searchApi()
            ->expression("folder:{$folder} AND public_id:{$partialName}*")
            ->maxResults(1)
            ->execute();

        if (!empty($result['resources'])) {
            $publicId = $result['resources'][0]['public_id'];
            return "https://res.cloudinary.com/{$cloudName}/image/upload/{$publicId}.{$format}";
        }

        return null;
    }
}
