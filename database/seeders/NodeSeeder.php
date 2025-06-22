<?php

namespace Database\Seeders;

use App\Models\Node;
use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nodes = [
            // Warehouses
            [
                'node_name' => 'Main Distribution Center',
                'node_type' => 'WAREHOUSE',
                'address' => '100 Logistics Way, Warehouse District, NY 10001, USA',
                'city' => 'New York',
                'country' => 'USA',
            ],
            [
                'node_name' => 'European Fulfillment Center',
                'node_type' => 'WAREHOUSE',
                'address' => 'Rotterdam Harbor 25, 3089 JW Rotterdam, Netherlands',
                'city' => 'Rotterdam',
                'country' => 'Netherlands',
            ],
            [
                'node_name' => 'Middle East Hub',
                'node_type' => 'WAREHOUSE',
                'address' => 'JAFZA Warehouse Complex, Block A4, Dubai, UAE',
                'city' => 'Dubai',
                'country' => 'UAE',
            ],

            // Ports
            [
                'node_name' => 'Port of Long Beach',
                'node_type' => 'PORT',
                'address' => 'Harbor Plaza, Long Beach, CA 90802, USA',
                'city' => 'Long Beach',
                'country' => 'USA',
            ],
            [
                'node_name' => 'Port of Shanghai',
                'node_type' => 'PORT',
                'address' => 'Waigaoqiao Free Trade Zone, Shanghai, China',
                'city' => 'Shanghai',
                'country' => 'China',
            ],
            [
                'node_name' => 'Port of Rotterdam',
                'node_type' => 'PORT',
                'address' => 'Maasvlakte, 3199 LK Rotterdam, Netherlands',
                'city' => 'Rotterdam',
                'country' => 'Netherlands',
            ],

            // Airports
            [
                'node_name' => 'JFK Cargo Terminal',
                'node_type' => 'AIRPORT',
                'address' => 'JFK International Airport, Jamaica, NY 11430, USA',
                'city' => 'New York',
                'country' => 'USA',
            ],
            [
                'node_name' => 'Dubai International Air Cargo',
                'node_type' => 'AIRPORT',
                'address' => 'Dubai International Airport, Cargo Village, Dubai, UAE',
                'city' => 'Dubai',
                'country' => 'UAE',
            ],

            // Land Depots
            [
                'node_name' => 'Central Land Hub',
                'node_type' => 'LAND_DEPOT',
                'address' => '789 Trucking Blvd, Dallas, TX 75201, USA',
                'city' => 'Dallas',
                'country' => 'USA',
            ],
            [
                'node_name' => 'European Distribution Center',
                'node_type' => 'LAND_DEPOT',
                'address' => 'Ruhrgebiet 124, 45141 Essen, Germany',
                'city' => 'Essen',
                'country' => 'Germany',
            ],

            // Customer Addresses
            [
                'node_name' => 'Global Logistics Co. - Head Office',
                'node_type' => 'CUSTOMER_ADDRESS',
                'address' => '123 Shipping Lane, Portcity, CA 92101, USA',
                'city' => 'Portcity',
                'country' => 'USA',
            ],
            [
                'node_name' => 'East Asia Shipping - Shanghai Office',
                'node_type' => 'CUSTOMER_ADDRESS',
                'address' => '789 Pudong Avenue, Shanghai, 200120, China',
                'city' => 'Shanghai',
                'country' => 'China',
            ],
        ];

        foreach ($nodes as $node) {
            Node::create($node);
        }
    }
}
