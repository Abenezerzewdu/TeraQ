<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Queue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $business = Business::create([
        'name' => 'TeraQ Barbershop',
        'location' => 'Addis Ababa'
    ]);

    Queue::create([
        'business_id' => $business->id,
        'name' => 'Haircut Queue',
        'status' => 'open',
        'avg_service_time' => 10
    ]);
    }
}
