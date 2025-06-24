<?php

namespace Database\Factories;

use App\Models\ShipmentItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ShipmentItemFactory extends Factory
{
    protected $model = ShipmentItem::class;

    public function definition(): array
    {
        return [
            'weight' => $this->faker->randomFloat(),
            'hight' => $this->faker->word(),
            'width' => $this->faker->word(),
            'count' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
