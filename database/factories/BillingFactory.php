<?php

namespace Database\Factories;

use App\Models\Billing;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Billing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' =>$this->faker->userName,
            'mobile_number'=> $this->faker->phoneNumber,
            'amount_to_bill' =>$this->faker->numberBetween(1000,5000)
        ];
    }
}
