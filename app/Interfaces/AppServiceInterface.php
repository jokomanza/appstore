<?php

namespace App\Interfaces;

interface AppServiceInterface
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
    public function handleUploadedIcon($packageName, $icon, $currentTime);

    /**
     * Handle uploaded user documentation file
     * 
     * @param string $packageName
     * @param \Illuminate\Http\UploadedFile $userDoc
     * @param int $currentTime Current timestamp
     * 
     * @return string Return saved file url when $userDoc not null, otherwise null
     */
    public function handleUploadedUserDocumentation($packageName, $userDoc, $currentTime);

    /**
     * Handle uploaded developer documentation file
     * 
     * @param string $packageName
     * @param \Illuminate\Http\UploadedFile $developerDoc
     * @param int $currentTime Current timestamp
     * 
     * @return string Return saved file url when $developerDoc not null, otherwise null
     */
    public function handleUploadedDeveloperDocumentation($packageName, $developerDoc, $currentTime);

    /**
     * Handle uploaded file when operation failed
     * 
     * @param string $packageName
     * @param int $currentTime Current timestamp
     * 
     * @return void
     */
    public function handleUploadedFileWhenFailed($packageName, $currentTime);

    /**
     * Handle uploaded file when operation failed
     * 
     * @param \App\Models\App $application
     * @param array $versions
     * 
     * @return void
     */
    public function handleDeletedApp(\App\Models\App $application, array $versions);
}
