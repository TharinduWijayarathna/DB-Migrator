<?php

namespace Database\Factories;

use App\Models\DBConnection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DBConnection>
 */
class ConnectionFactory extends Factory
{
    protected $model = DBConnection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'host' => $this->faker->ipv4,
            'port' => $this->faker->numberBetween(1, 65535),
            'database' => $this->faker->word,
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ];
    }

    /**
     * Indicate that the model is inactive.
     *
     * @return \Database\Factories\ConnectionFactory
     */
    public function inactive(): ConnectionFactory
    {
        return $this->state(fn(array $attributes) => ['is_active' => false]);
    }
}
