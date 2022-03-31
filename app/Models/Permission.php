<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @property int $id
 * @property int $app_id
 * @property string $user_registration_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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

    /**
     * Check whether user has permission on specific app
     *
     * @param $appId
     * @param $registrationNumber
     * @return bool
     */
    public function hasPermission($appId, $registrationNumber)
    {
        return $this->where([
                'app_id' => $appId,
                'user_registration_number' => $registrationNumber
            ])->first() != null;
    }

    /**
     * Get permission and its user based on app id.
     *
     * @param $appId
     * @return Permission[]
     */
    public function getPermissionsWithUser($appId)
    {
        return $this->with('user')->where('app_id', $appId)->get();
    }

    /**
     * Get permission based on app package name and user registration number.
     *
     * @param $packageName
     * @param $registrationNumber
     * @return Permission|null
     */
    public function getPermission($packageName, $registrationNumber)
    {
        return $this->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->where('user_registration_number', $registrationNumber)->first();
    }
}
