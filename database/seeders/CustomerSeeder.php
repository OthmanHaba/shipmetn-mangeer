<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'customer_name' => 'Global Logistics Co.',
                'contact_person' => 'John Smith',
                'phone_number' => '+1 555-123-4567',
                'address' => '123 Shipping Lane, Portcity, CA 92101, USA',
            ],
            [
                'customer_name' => 'Mediterranean Import/Export',
                'contact_person' => 'Maria Rossi',
                'phone_number' => '+39 06 5555 7890',
                'address' => 'Via del Porto 45, Rome, 00154, Italy',
            ],
            [
                'customer_name' => 'Gulf Trading LLC',
                'contact_person' => 'Ahmed Al-Mansour',
                'phone_number' => '+971 4 123 4567',
                'address' => 'Dubai Trade Center, Sheikh Zayed Road, Dubai, UAE',
            ],
            [
                'customer_name' => 'East Asia Shipping',
                'contact_person' => 'Li Wei',
                'phone_number' => '+86 21 6123 4567',
                'address' => '789 Pudong Avenue, Shanghai, 200120, China',
            ],
            [
                'customer_name' => 'African Distribution Services',
                'contact_person' => 'Kwame Osei',
                'phone_number' => '+233 302 123 456',
                'address' => '45 Independence Avenue, Accra, Ghana',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
