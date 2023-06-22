<?php

namespace App\Util;

use RuntimeException;
use Gumlet\ImageResize;
use Gumlet\ImageResizeException;

class ImageHandler
{
    public static function createResizedImageFile(
        string $path,
        int $width,
        int $height
    ): void {
        try {
            $image = new ImageResize($path);

            $image->resize($width, $height);
            $image->save(self::resolvePathToResizedImage($path, $width, $height));
        } catch (ImageResizeException) {
        }
    }

    public static function resolvePathToResizedImage(string $path, int $width, int $height): string
    {
        $pathInfo = pathinfo($path);

        if (!array_key_exists('extension', $pathInfo)) {
            throw new RuntimeException(sprintf('pathInfo() did not return an extension for path "%s".', $path));
        }

        return sprintf(
            '%s/%s_%dx%d.%s',
            $pathInfo['dirname'],
            $pathInfo['filename'],
            $width,
            $height,
            $pathInfo['extension']
        );
    }
}
