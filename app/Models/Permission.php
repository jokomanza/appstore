<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * Class Developer
 * 
 * @property int $id
 * @property int $app_id
 * @property string $user_registration_number
 * @property timestamp without time zone|null $created_at
 * @property timestamp without time zone|null $updated_at
 * 
 * @property App $app
 * @property User $user
 *
 * @package App\Models
 */
class Permission extends Model
{
    protected $casts = [
		'app_id' => 'int',
		'user_registration_number' => 'character varying',
		'created_at' => 'timestamp without time zone',
		'updated_at' => 'timestamp without time zone'
	];

	protected $fillable = [
		'app_id',
		'user_registration_number',
		'type'
	];

	public function app()
	{
		return $this->belongsTo(App::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
