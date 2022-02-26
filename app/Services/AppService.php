<?php

namespace App\Services;

use App\Interfaces\AppServiceInterface;
use Intervention\Image\ImageManager;

class AppService implements AppServiceInterface
{

    public function handleUploadedIcon($packageName, $icon, $currentTime)
    {
        if ($icon) {
            $extension = $icon->getClientOriginalExtension();

            $stored = $packageName . '.default_icon.' . $currentTime . ".$extension";

            $image = (new ImageManager())->make($icon);

            if (!$image->save(public_path('/storage/') . str_replace("$extension", 'jpg', $stored))) {
                throw new \Exception("Failed to save icon");
            }

            return $image->basename;
        }

        return null;
    }

    public function handleUploadedUserDocumentation($packageName, $userDoc, $currentTime)
    {
        if ($userDoc) {
            $stored = $packageName . '.user_documentation_file.' . $currentTime . '.pdf';

            if (!$userDoc->move(public_path('/storage/'), $stored)) {
                throw new \Exception('failed to save user documentation pdf file');
            }

            return $stored;
        }

        return null;
    }

    public function handleUploadedDeveloperDocumentation($packageName, $developerDoc, $currentTime)
    {
        if ($developerDoc) {
            $stored = $packageName . '.user_documentation_file.' . $currentTime . '.pdf';

            if (!$developerDoc->move(public_path('/storage/'), $stored)) {
                throw new \Exception('failed to save developer documentation pdf file');
            }

            return $stored;
        }

        return null;
    }

    public function handleUploadedFileWhenFailed($packageName, $currentTime)
    {
        @unlink(public_path('/storage/') . "$packageName.default_icon.$currentTime.jpg");
        @unlink(public_path('/storage/') . "$packageName.user_documentation.$currentTime.pdf");
        @unlink(public_path('/storage/') . "$packageName.developer_documentation.$currentTime.pdf");
    }
}
