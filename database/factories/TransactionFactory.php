<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['Purchase', 'Application']);

        return [
            'date' => $this->faker->dateTime,
            'type' => $type,
            'quantity' => $this->faker->randomNumber(2, false),
            'unit_price' => $type === 'Purchase' ? $this->faker->randomFloat(2, 0.1, 100) : 0,
        ];
    }

    /**
     * Indicate that only purchases are made.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function purchases()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Purchase',
            ];
        });
    }

    /**
     * Indicate that only purchases are made.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function applications()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Application',
            ];
        });
    }

    /**
     * Indicate that the quantity should be very high
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function high()
    {
        return $this->state(function (array $attributes) {
            return [
                'quantity' => $this->faker->randomNumber(3, true),
            ];
        });
    }
}
