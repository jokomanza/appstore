<?php

namespace App\Services;

use App\Interfaces\AppVersionServiceInterface;
use Intervention\Image\ImageManager;

class AppVersionService implements AppVersionServiceInterface
{
    function handleUploadedIcon($packageName, \Illuminate\Http\UploadedFile $icon, $currentTime)
    {
        if ($icon) {
            $extension = $icon->getClientOriginalExtension();

            $stored = $packageName . '.icon.' . $currentTime . ".$extension";

            $image = (new ImageManager())->make($icon);

            if (!$image->save(public_path('/storage/') . str_replace("$extension", 'jpg', $stored))) {
                throw new \Exception("Failed to save icon");
            }

            return $image->basename;
        }

        return null;
    }


    function handleUploadedApk($packageName, \Illuminate\Http\UploadedFile $apkFile, $currentTime)
    {
        if ($apkFile) {
            $extension = $apkFile->getClientOriginalExtension();
            $size = $apkFile->getSize();

            if ($extension != 'apk') {
                throw new \Exception("Application file extension must be *.apk");
            }

            $name = $packageName . '.' . $currentTime . ".apk";

            if (!$apkFile->move(public_path('/storage/'), $name)) {
                throw new \Exception("Failed to save apk file");
            }

            return ["name" => $name, "size" => $size];
        }

        return null;
    }
    function handleDeletedVersion(\App\Models\AppVersion $version)
    {
        return @unlink(public_path('/storage/') . $version->apk_file_url) && @unlink(public_path('/storage/') . $version->icon_url);

    }
}
