<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

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
        $orderLine->comment = 'Soep goed verwarmen';
        $order->orderLines()->save($orderLine);

        $csv = Reader::createFromPath(database_path('sales_data.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv->getRecords() as $record) {
            $order = new Order();
            $order->is_takeaway = false;
            $order->created_at = $record['saleDate'];
            $order->save();

            $orderLine = new OrderLine();
            $orderLine->dish_id = $record['itemId'];
            $orderLine->amount = $record['amount'];
            $orderLine->created_at = $record['saleDate'];
            $order->orderLines()->save($orderLine);
        }
    }
}
