<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class App extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'name' => 'character varying',
        'package_name' => 'character varying',
        'description' => 'character varying',
        'type' => 'character varying',
        'icon_url' => 'character varying',
        'repository_url' => 'character varying',
        'user_documentation_url' => 'character varying',
        'developer_documentation_url' => 'character varying',
        'created_at' => 'timestamp without time zone',
        'updated_at' => 'timestamp without time zone'
    ];

    protected $fillable = [
        'name',
        'package_name',
        'description',
        'type',
        'icon_url',
        'repository_url',
        'user_documentation_url',
        'developer_documentation_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'api_token',
    ];

    /**
     * Save this to database
     *
     * @return boolean
     */
    public function saveData()
    {
        return $this->save();
    }

    /**
     * Update this data
     *
     * @return boolean
     */
    public function updateData()
    {
        return $this->update();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function developers()
    {
        return $this->hasMany(Developer::class);
    }

    /**
     * Get version from version name
     *
     * @param $versionName string
     * @return AppVersion|null
     */
    public function getVersion($versionName)
    {
        return $this->app_versions()->where('version_name', $versionName)->first();

        return AppVersion::where('app_id', $this->id)
            ->where('version_name', $versionName)
            ->first();
    }

    public function app_versions()
    {
        return $this->hasMany(AppVersion::class);
    }

    /**
     * Get app base on its package names.
     *
     * @param $packageName
     * @return App|null
     */
    public function getApp($packageName)
    {
        return $this->where('package_name', $packageName)->first();
    }

    /**
     * @param $packageName
     * @return App|null
     */
    public function getAppWithVersions($packageName)
    {
        return $this->with('app_versions')->where('package_name', $packageName)->first();
    }
}
