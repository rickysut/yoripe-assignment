<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posts>
 */
class PostsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realTextBetween($minNbChars = 6, $maxNbChars = 20, $indexSize = 2),
            'body' => $this->faker->realText($maxNbChars = 200, $indexSize = 2),
            'user_id' => 1,

        ];
    }
}
