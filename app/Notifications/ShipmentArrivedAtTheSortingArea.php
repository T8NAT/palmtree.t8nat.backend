<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\SendFireBaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ShipmentArrivedAtTheSortingArea extends Notification
{
    use Queueable;

    protected $firebase_tokens;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($firebase_tokens,$order)
    {
        //
        $this->firebase_tokens = $firebase_tokens;
        $this->order           = $order;
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
            "title" => 'وصلت الشحنه لمنطقه الفرز',
            "body"  => 'وصلت الشحنه رقم' . $this->order->unique_id.' لمنطقه الفرز '
        ];
        SendFireBaseNotification::send_firebase_notifiy($this->firebase_tokens,$notification,[
            'type' => 'shipment_arrived_at_the_sorting_area'
        ]);

        // shipment_left_the_sorting_area
    }

     /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            "title" => 'وصلت الشحنه لمنطقه الفرز',
            "body"  => 'وصلت الشحنه رقم' . $this->order->unique_id.' لمنطقه الفرز '
        ];
    }
}
