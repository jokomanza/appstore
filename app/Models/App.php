<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

	public function app_versions()
	{
		return $this->hasMany(AppVersion::class);
	}

	public function reports()
	{
		return $this->hasMany(Report::class);
	}

	public function developers()
	{
		return $this->hasMany(Developer::class);
	}
}
