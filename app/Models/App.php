<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class App
 * 
 * @property int $id
 * @property character varying $name
 * @property character varying $package_name
 * @property character varying $description
 * @property character varying $type
 * @property character varying $icon_url
 * @property character varying|null $repository_url
 * @property character varying|null $user_documentation_url
 * @property character varying|null $developer_documentation_url
 * @property timestamp without time zone|null $created_at
 * @property timestamp without time zone|null $updated_at
 * 
 * @property Collection|Team[] $teams
 * @property Collection|AppVersion[] $app_versions
 *
 * @package App\Models
 */
class App extends Model
{
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
}
