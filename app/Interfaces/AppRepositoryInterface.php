<?php

namespace App\Interfaces;

interface AppRepositoryInterface
{
    public function getAllApps();
    public function getAppById($appId);
    public function getAppByPackageName($packageName);
    public function deleteApp($appId);
    public function createApp(array $appDetails);
    public function updateApp($appId, array $newDetails);
    public function getFulfilledApps();
    public function paginate($limit);
}
