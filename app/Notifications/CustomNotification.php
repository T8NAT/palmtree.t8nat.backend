<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\SendFireBaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomNotification extends Notification
{
    use Queueable;
    protected $notification;
    /**
     * Create a new notification instance.
     */
    public function __construct($notification)
    {
        //
        $this->notification    = $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $this->toFirebase($notifiable);
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toFirebase(object $notifiable)
    {
        $notification =  [
            "title" => $this->notification['title'],
            "body"  => $this->notification['body']
        ];
        SendFireBaseNotification::send_firebase_notifiy([$notifiable->device_token],$notification,[
            'type' => 'custom'
        ]);
    }

     /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            "title" => $this->notification['title'],
            "body"  => $this->notification['body']
        ];
    }
}
