<?php

namespace Database\Factories;

use App\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'date' => $this->faker->date('Y-m-d'),
            'status' => 'created',
            'isInformedDeletion' => false,
        ];
    }

    public function started(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'started',
        ]);
    }
}
