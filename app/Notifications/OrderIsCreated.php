<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\SendFireBaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderIsCreated extends Notification
{
    use Queueable;
    
    protected $firebase_tokens;

    /**
     * Create a new notification instance.
     */
    public function __construct($firebase_tokens)
    {
        //
        $this->firebase_tokens = $firebase_tokens;
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
            "title" => 'شحنة جديدة متاحة',
            "body"  => 'تم اضافة شحنة جديدة متاحة يمكنك طلب توصيلها'
        ];
        SendFireBaseNotification::send_firebase_notifiy($this->firebase_tokens,$notification,[
            'type' => 'new_order'
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
            "title" => 'شحنة جديدة متاحة',
            "body"  => 'تم اضافة شحنة جديدة متاحة يمكنك طلب توصيلها'
        ];
    }
}
