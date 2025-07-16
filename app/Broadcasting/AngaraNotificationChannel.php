<?php

namespace App\Broadcasting;

use App\Models\AngaraNotification;
use App\Models\User;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;

class AngaraNotificationChannel extends IlluminateDatabaseChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $notifiable->routeNotificationFor('database')) {
            return;
        }

        $data = $this->getData($notifiable, $notification);

        return AngaraNotification::create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'data' => $data,
            'read_at' => null,
        ]);
    }


}
