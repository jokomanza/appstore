<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AppVersion
 * 
 * @property int $id
 * @property int $app_id
 * @property int $version_code
 * @property character varying $version_name
 * @property int $min_sdk_level
 * @property int $target_sdk_level
 * @property character varying $apk_file_url
 * @property character varying $apk_file_size
 * @property character varying $icon_url
 * @property character varying|null $description
 * @property int $downloads
 * @property int $installs
 * @property timestamp without time zone|null $created_at
 * @property timestamp without time zone|null $updated_at
 * 
 * @property App $app
 * @property Collection|CatalogHeader[] $catalog_headers
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
}
