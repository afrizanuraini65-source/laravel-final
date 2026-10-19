<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->data['type'] ?? 'info',
            'message' => $this->data['message'] ?? 'Notification',
            'event' => $this->data['event'] ?? null,
            'url' => $this->data['url'] ?? '#'
        ];
    }
}
