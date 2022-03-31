<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class AppVersion
 *
 * @property int $id
 * @property int $app_id
 * @property int $version_code
 * @property string $version_name
 * @property int $min_sdk_level
 * @property int $target_sdk_level
 * @property string $apk_file_url
 * @property string $apk_file_size
 * @property string $icon_url
 * @property string|null $description
 * @property int $downloads
 * @property int $installs
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property App $app
 *
 * @package App\Models
 */
class AppVersion extends Model
{
    protected $casts = [
        'app_id' => 'int',
        'version_code' => 'int',
        'version_name' => 'character varying',
        'min_sdk_level' => 'int',
        'target_sdk_level' => 'int',
        'apk_file_url' => 'character varying',
        'apk_file_size' => 'character varying',
        'icon_url' => 'character varying',
        'description' => 'character varying',
        'downloads' => 'int',
        'installs' => 'int',
        'created_at' => 'timestamp without time zone',
        'updated_at' => 'timestamp without time zone'
    ];

    protected $fillable = [
        'app_id',
        'version_code',
        'version_name',
        'min_sdk_level',
        'target_sdk_level',
        'apk_file_url',
        'apk_file_size',
        'icon_url',
        'description',
        'downloads',
        'installs'
    ];

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    /**
     * Get all versions of an app based on app id.
     *
     * @param $appId
     * @return AppVersion[]|Collection
     */
    public function getVersions($appId)
    {
        return $this->where('app_id', $appId)->get();
    }

    /**
     * Get version with app based on apps package name and version name.
     *
     * @param $packageName
     * @param $versionName
     * @return AppVersion|null
     */
    public function getVersion($packageName, $versionName)
    {
        return $this->where('version_name', $versionName)
            ->whereHas('app', function ($q) use ($packageName) {
                $q->where('package_name', $packageName);
            })->first();
    }

    /**
     * @return AppVersion[]|Collection|\Illuminate\Support\Collection
     */
    public function getChartData()
    {
        return $this->select(DB::raw("TO_CHAR(DATE(created_at) :: DATE, 'Mon dd, yyyy') as x"), DB::raw('count(*) as y'))
            ->groupBy('x')
            ->get();
    }
}
