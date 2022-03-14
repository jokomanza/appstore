<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 * 
 * @property Carbon|null $updated_at
 * @property Carbon|null $created_at
 * @property string $shared_preferences
 * @property string $environment
 * @property string $device_features
 * @property character varying $installation_id
 * @property string $logcat
 * @property character varying|null $dumpsys_meminfo
 * @property character varying $user_crash_date
 * @property character varying $user_app_start_date
 * @property character varying|null $user_email
 * @property character varying|null $user_comment
 * @property string $display
 * @property string $crash_configuration
 * @property string $initial_configuration
 * @property character varying $exception
 * @property string $stack_trace
 * @property character varying|null $is_silent
 * @property character varying|null $custom_data
 * @property character varying $build_config
 * @property character varying $available_mem_size
 * @property character varying $total_mem_size
 * @property string $build
 * @property character varying $android_version
 * @property character varying $product
 * @property character varying $brand
 * @property character varying $phone_model
 * @property character varying $file_path
 * @property character varying $package_name
 * @property character varying $app_version_name
 * @property character varying $app_version_code
 * @property int $application_id
 * @property character varying $report_id
 * @property int $id
 * 
 * @property Application $application
 *
 * @package App\Models
 */
class Report extends Model
{
	protected $table = 'report';

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
		'application_id',
		'report_id'
	];

	public function application()
	{
		return $this->belongsTo(Application::class);
	}
}
