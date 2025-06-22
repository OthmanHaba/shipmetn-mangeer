<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in order of dependencies
        $this->call([
            UserSeeder::class,
            // Then seed master data
            CustomerSeeder::class,
            NodeSeeder::class,
            ChartOfAccountSeeder::class,
            // Then seed transactional data
            ShipmentSeeder::class,
            InvoiceSeeder::class,
            ExpenseSeeder::class,
            JournalEntrySeeder::class,
        ]);
    }
}
