<?php

namespace App\Interfaces;

interface AppServiceInterface
{
    /**
     * Handle uploaded icon
     * 
     * @param string $packageName
     * @param icon $icon
     * @param time $currentTime
     * 
     * @return string Return saved file url when $icon not null, otherwise null
     */
    public function handleUploadedIcon($packageName, $icon, $currentTime);

    /**
     * Handle uploaded user documentation file
     * 
     * @param string $packageName
     * @param icon $userDoc
     * @param time $currentTime
     * 
     * @return string Return saved file url when $userDoc not null, otherwise null
     */
    public function handleUploadedUserDocumentation($packageName, $userDoc, $currentTime);

    /**
     * Handle uploaded developer documentation file
     * 
     * @param string $packageName
     * @param icon $developerDoc
     * @param time $currentTime
     * 
     * @return string Return saved file url when $developerDoc not null, otherwise null
     */
    public function handleUploadedDeveloperDocumentation($packageName, $developerDoc, $currentTime);

    /**
     * Handle uploaded file when operation failed
     * 
     * @param string $packageName
     * @param time $currentTime
     * 
     * @return void
     */
    public function handleUploadedFileWhenFailed($packageName, $currentTime);
}
