<?php

namespace App;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'notifiable_id');
    }
}
