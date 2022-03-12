<?php

namespace App\Interfaces;

interface AppVersionServiceInterface
{

    /**
     * Handle uploaded icon
     * 
     * @param string $packageName
     * @param \Illuminate\Http\UploadedFile $icon
     * @param int $currentTime Current timestamp
     * 
     * @return string Return saved file url when $icon not null, otherwise null
     */
    public function handleUploadedIcon($packageName,\Illuminate\Http\UploadedFile $icon, $currentTime);

    /**
     * Handle uploaded apk file
     * 
     * @param string $packageName
     * @param \Illuminate\Http\UploadedFile $apkFile
     * @param int $currentTime Current timestamp
     * 
     * @return array Return saved file url and file size when successfully, otherwise null
     */
    public function handleUploadedApk($packageName,\Illuminate\Http\UploadedFile $apkFile, $currentTime);

    /**
     * Handle when version was deleted
     * 
     * @param \App\Models\AppVersion $version
     * 
     * @return bool Return true if operation was successful, otherwise false
     */
    public function handleDeletedVersion(\App\Models\AppVersion $version);
}
