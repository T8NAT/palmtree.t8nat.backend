<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $statuses = [
            ['status_name' => 'pending'],
            ['status_name' => 'accepted'],
            ['status_name' => 'heading_to_destination'],
            ['status_name' => 'arrived_at_destination'],
            ['status_name' =>'received_by_courier'],
            ['status_name' => 'delivery_in_progress'],
            ['status_name' => 'arrived_at_customer'],
            ['status_name' => 'delivered'],
            ['status_name' => 'canceled'],
            ['status_name' => 'returned']
        ];
        
        Status::insert($statuses);
    }
}
