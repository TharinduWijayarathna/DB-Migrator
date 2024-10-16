<?php

namespace Database\Seeders;

use App\Models\DBConnection;
use Database\Factories\ConnectionFactory;
use Illuminate\Database\Seeder;

class ConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 100 connections using the factory
        ConnectionFactory::new()->count(100)->create();
    }
}
