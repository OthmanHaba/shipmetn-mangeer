<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Node;
use App\Models\Shipment;
use App\Models\ShipmentLeg;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample shipments
        $shipments = [
            [
                'reference_id' => 'SHP-2025-06-0001',
                'shipping_mode' => 'SEA',
                'status' => 'IN_TRANSIT',
                'shipper_customer_id' => Customer::where('customer_name', 'Global Logistics Co.')->first()->id,
                'consignee_customer_id' => Customer::where('customer_name', 'Mediterranean Import/Export')->first()->id,
                'estimated_departure' => Carbon::now()->subDays(7),
                'estimated_arrival' => Carbon::now()->addDays(14),
                'actual_departure' => Carbon::now()->subDays(6),
                'shipment_price' => 5000.00,
                'actual_arrival' => null,
            ],
            [
                'reference_id' => 'SHP-2025-06-0002',
                'shipping_mode' => 'AIR',
                'status' => 'DELIVERED',
                'shipper_customer_id' => Customer::where('customer_name', 'East Asia Shipping')->first()->id,
                'consignee_customer_id' => Customer::where('customer_name', 'Gulf Trading LLC')->first()->id,
                'estimated_departure' => Carbon::now()->subDays(10),
                'estimated_arrival' => Carbon::now()->subDays(8),
                'actual_departure' => Carbon::now()->subDays(10),
                'shipment_price' => 5000.00,
                'actual_arrival' => Carbon::now()->subDays(8),
            ],
            [
                'reference_id' => 'SHP-2025-06-0003',
                'shipping_mode' => 'LAND',
                'status' => 'PENDING',
                'shipper_customer_id' => Customer::where('customer_name', 'African Distribution Services')->first()->id,
                'consignee_customer_id' => Customer::where('customer_name', 'Global Logistics Co.')->first()->id,
                'estimated_departure' => Carbon::now()->addDays(3),
                'estimated_arrival' => Carbon::now()->addDays(8),
                'shipment_price' => 5000.00,

                'actual_departure' => null,
                'actual_arrival' => null,
            ],
        ];

        foreach ($shipments as $shipment) {
            $newShipment = Shipment::create($shipment);

            // Create legs for each shipment
            if ($newShipment->shipping_mode === 'SEA' && $newShipment->id === 1) {
                // Create legs for the first sea shipment
                $legs = [
                    [
                        'shipment_id' => $newShipment->id,
                        'sequence_order' => 1,
                        'origin_node_id' => Node::where('node_name', 'Port of Long Beach')->first()->id,
                        'destination_node_id' => Node::where('node_name', 'Port of Rotterdam')->first()->id,
                        'status' => 'IN_PROGRESS',
                        'departure_timestamp' => Carbon::now()->subDays(6),
                        'arrival_timestamp' => Carbon::now()->addDays(8),
                    ],
                    [
                        'shipment_id' => $newShipment->id,
                        'sequence_order' => 2,
                        'origin_node_id' => Node::where('node_name', 'Port of Rotterdam')->first()->id,
                        'destination_node_id' => Node::where('node_name', 'European Fulfillment Center')->first()->id,
                        'status' => 'PENDING',
                        'departure_timestamp' => Carbon::now()->addDays(9),
                        'arrival_timestamp' => Carbon::now()->addDays(10),
                    ],
                ];

                foreach ($legs as $leg) {
                    ShipmentLeg::create($leg);
                }
            } elseif ($newShipment->shipping_mode === 'AIR' && $newShipment->id === 2) {
                // Create a leg for the air shipment
                ShipmentLeg::create([
                    'shipment_id' => $newShipment->id,
                    'sequence_order' => 1,
                    'origin_node_id' => Node::where('node_name', 'JFK Cargo Terminal')->first()->id,
                    'destination_node_id' => Node::where('node_name', 'Dubai International Air Cargo')->first()->id,
                    'status' => 'COMPLETED',
                    'departure_timestamp' => Carbon::now()->subDays(10),
                    'arrival_timestamp' => Carbon::now()->subDays(8),
                ]);
            } elseif ($newShipment->shipping_mode === 'LAND' && $newShipment->id === 3) {
                // Create legs for the land shipment
                $legs = [
                    [
                        'shipment_id' => $newShipment->id,
                        'sequence_order' => 1,
                        'origin_node_id' => Node::where('node_name', 'Central Land Hub')->first()->id,
                        'destination_node_id' => Node::where('node_name', 'Main Distribution Center')->first()->id,
                        'status' => 'PENDING',
                        'departure_timestamp' => Carbon::now()->addDays(3),
                        'arrival_timestamp' => Carbon::now()->addDays(8),
                    ],
                ];

                foreach ($legs as $leg) {
                    ShipmentLeg::create($leg);
                }
            }
        }
    }
}
