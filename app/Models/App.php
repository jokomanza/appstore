<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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

    /**
     * @return HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * @return HasMany
     */
    public function developers()
    {
        return $this->hasMany(Developer::class);
    }

    /**
     * Check if this app is client application.
     *
     * @return bool return true if this is client app, otherwise false
     */
    public function isClientApp()
    {
        return $this->package_name == config('app.client_package_name');
    }

    /**
     * Get version from version name
     *
     * @param $versionName string
     * @return AppVersion|null
     */
    public function getVersion($versionName)
    {
        return $this->versions()->where('version_name', $versionName)->first();
    }

    /**
     * @return HasMany
     */
    public function versions()
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

    /**
     * @return App|Model|Builder|null
     */
    public function getClientApp()
    {
        return $this->where('package_name', config('app.client_package_name'))->first();
    }

    /**
     * @return App[]|Collection|\Illuminate\Support\Collection
     */
    public function getChartData()
    {
        return $this->select(DB::raw("DATE(created_at) as x"), DB::raw('count(*) as y'))
            ->orderBy('x', 'ASC')
            ->groupBy('x')
            ->get()->map(function ($value) {
                return ['x' => Carbon::parse($value->x)->toFormattedDateString(), 'y' => $value->y];
            });
    }
}
