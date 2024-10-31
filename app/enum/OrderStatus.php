<?php 
namespace App\enum;
class OrderStatus {

    const Pending              = 'pending';
    const Accepted             = 'accepted';
    const HeadingToDestination = 'heading_to_destination';
    const ArrivedAtDestination = 'arrived_at_destination';
    const ReceivedByCourier    = 'received_by_courier';
    const DeliveryInProgress   = 'delivery_in_progress';
    const ArrivedAtCustomer    = 'arrived_at_customer';
    const Delivered            = 'delivered';
    const Canceled             = 'canceled';
    const Returned             = 'returned';
}