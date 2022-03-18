<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\App;
use App\Models\Permission;

class User extends Authenticatable
{
    use Notifiable;

	protected $primaryKey = 'registration_number';
	public $incrementing = false;

    /**
     * The attributes that are mass assignable.
	 * 
     * @var array
     */
    protected $fillable = [
        'registration_number', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isDeveloperOf($app)
    {
        if ($app == null) return false;

        return Permission::where(['user_registration_number' => $this->registration_number, 'app_id' => $app->id, 'type' => 'developer'])->first();
    }

    public function isOwnerOf(App $app)
    {
        return Permission::where(['user_registration_number' => $this->registration_number, 'app_id' => $app->id, 'type' => 'owner'])->first();
    }
}
