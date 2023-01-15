<?php

namespace App\Enums;

use App\Data\Dimensions;

enum Format: string
{
    case YOUTUBE = 'youtube';
    case TIKTOK = 'tiktok';

    public function getDimensions(): Dimensions
    {
        return match($this) {
            self::YOUTUBE => new Dimensions(1920, 1080),
            self::TIKTOK => new Dimensions(1080, 1920),
        };
    }
}
