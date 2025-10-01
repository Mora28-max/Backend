<?php

namespace App\Traits;

use App\Helpers\UploadDataToCloudinary;

trait HasDefaultImage
{
    protected function getImageUrl(?string $image, string $type = 'image', string $format = 'jpg'): string
    {
        if ($image) {
            return UploadDataToCloudinary::getSignedAuthenticatedUrl($image, $type, $format);
        }
        return 'https://res.cloudinary.com/dn2wntbns/image/upload/v1754422085/not-available-avatar_q3v7bx.jpg';
    }
}
