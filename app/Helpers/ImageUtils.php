<?php

namespace App\Helpers;

use Image;

class ImageUtils
{
    const IMG_SIZE_THUMB = "150x150";
    const IMG_SIZE_SMALL = "200x150";
    const IMG_SIZE_MEDIUM = "550x412";
    const IMG_SIZE_LARGE = "880x400";

    public static function saveOriginalImage($pathSaveImage, $imageName)
    {
        $image  = Image::make($pathSaveImage . "/" . $imageName);
        $width  = $image->getWidth();
        $height = $image->getHeight();
        if ($width > $height) {
            $width = 1280;
        } else {
            $height = 800;
        }
        $imageThumb = $image;
        $imageThumb->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $imageThumb->save($pathSaveImage . "/" . $imageName, 80);
    }

    public static function getUrlImage($path, $imageName, $size)
    {
        $pathImage = $path . "/" . $size . "/" . $imageName;
        if (!file_exists(public_path($pathImage))) {
            $createThumbnail = self::createThumbnail($path, $imageName, $size);
            if ($createThumbnail !== null) {
                $pathImage = $createThumbnail;
            } else {
                return null;
            }
        }

        return url($pathImage);
    }

    public static function createThumbnail($path, $imageName, $size)
    {
        $size  = explode("x", $size);
        $width = $size[0];
        if (file_exists($path . "/" . $imageName)) {
            $image = \Image::make($path . "/" . $imageName);
        } else {
            return null;
        }
        if ($width < 800) {
            $height = $size[1];
        } else {
            $height = $image->getHeight();
        }

        if ($width > 150) {
            $imageThumb = $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $imageThumb = $image->fit($width, $height);
        }
        $dirImage = $path . "/" . $width . "x" . $height;
        if (!is_dir(public_path($dirImage))) {
            mkdir(public_path($dirImage));
        }
        $imageThumb->save(public_path($dirImage) . "/" . $imageName);

        return url($dirImage . "/" . $imageName);
    }
}
