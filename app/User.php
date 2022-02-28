<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
