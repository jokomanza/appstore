<?php

namespace App\Repositories;

use App\Interfaces\AppRepositoryInterface;
use App\Models\App;

class AppRepository implements AppRepositoryInterface
{
    
    public function getAllApps()
    {
        return App::all();
    }

    public function getAppById($appId)
    {
        return App::findOrFail($appId);
    }
    
    public function getAppByPackageName($packageName)
    {
        return App::where(['package_name' => $packageName])->first();
    }
    
    public function deleteApp($appId)
    {
        return App::destroy($appId);
    }
    
    public function createApp(array $appDetails)
    {
        return App::create($appDetails);
    }
    
    public function updateApp($appId, array $newDetails)
    {
        return App::find($appId)->update($newDetails);
    }
    
    public function getFulfilledApps()
    {
        return App::where('is_fulfilled', true);
    }

    public function paginate($limit)
    {
        return App::paginate($limit);
    }
}
