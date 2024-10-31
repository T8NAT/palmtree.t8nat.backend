<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use App\Services\SmsService;

class SerialDelivery extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $this->toSms($notifiable);
        return [];
    }

     /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable)
    {
        $body =  __("Your Serial Code is :serialNo for order number :orderNo",[
            'serialNo' => $this->order->serialNo,
            'orderNo'  => $this->order->unique_id
        ]);
        SmsService::send_sms($this->order->customer_phone,$body);
    }



    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
