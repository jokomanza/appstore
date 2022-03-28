<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    public function users()
    {
        return $this->notifiable();
        return $this->belongsTo(User::class, 'notifiable_id');
    }
}
