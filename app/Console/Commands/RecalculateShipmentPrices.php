<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use Illuminate\Console\Command;

class RecalculateShipmentPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipments:recalculate-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all shipment prices based on their items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to recalculate shipment prices...');

        $shipments = Shipment::all();
        $bar = $this->output->createProgressBar($shipments->count());

        $bar->start();

        foreach ($shipments as $shipment) {
            $shipment->calculateTotalPrice();
            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info('All shipment prices have been recalculated successfully!');

        return Command::SUCCESS;
    }
}
