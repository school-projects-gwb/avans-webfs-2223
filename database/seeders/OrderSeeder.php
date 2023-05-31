<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = new Order();
        $order->first_name = "Piet";
        $order->last_name ="Janssen";
        $order->is_takeaway = false;
        $order->save();

        $orderLine = new OrderLine();
        $orderLine->dish_id = 1;
        $orderLine->amount = 1;
        $order->orderLines()->save($orderLine);
    }
}
