<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Report extends Model
{

    protected $casts = [
        // 'updated_at' => 'timestamp without time zone',
        // 'created_at' => 'timestamp without time zone',
        'build' => 'json',
        'build_config' => 'json',
        'custom_data' => 'json',
        'initial_configuration' => 'json',
        'crash_configuration' => 'json',
        'display' => 'json',
        'device_features' => 'json',
        'environment' => 'json',
        'shared_preferences' => 'json',
        'installation_id' => 'character varying',
        'dumpsys_meminfo' => 'character varying',
        'user_crash_date' => 'character varying',
        'user_app_start_date' => 'character varying',
        'user_email' => 'character varying',
        'user_comment' => 'character varying',
        'exception' => 'character varying',
        'is_silent' => 'character varying',
        'available_mem_size' => 'character varying',
        'total_mem_size' => 'character varying',
        'android_version' => 'character varying',
        'product' => 'character varying',
        'brand' => 'character varying',
        'phone_model' => 'character varying',
        'file_path' => 'character varying',
        'package_name' => 'character varying',
        'app_version_name' => 'character varying',
        'app_version_code' => 'character varying',
        'application_id' => 'int',
        'report_id' => 'character varying'
    ];

    protected $fillable = [
        'shared_preferences',
        'environment',
        'device_features',
        'installation_id',
        'logcat',
        'dumpsys_meminfo',
        'user_crash_date',
        'user_app_start_date',
        'user_email',
        'user_comment',
        'display',
        'crash_configuration',
        'initial_configuration',
        'exception',
        'stack_trace',
        'is_silent',
        'custom_data',
        'build_config',
        'available_mem_size',
        'total_mem_size',
        'build',
        'android_version',
        'product',
        'brand',
        'phone_model',
        'file_path',
        'package_name',
        'app_version_name',
        'app_version_code',
        'app_id',
        'report_id'
    ];

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    /**
     * Get all the reports based on app id
     *
     * @param $appId
     * @return Report[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public function getReportsByAppId($appId)
    {
        return $this->where('app_id', $appId)->get();
    }

    /**
     * @return Report[]|Collection|\Illuminate\Support\Collection
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
