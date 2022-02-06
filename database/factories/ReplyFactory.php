<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_id' => function () {
                return \App\Models\User::factory()->create()->id;
            } ,
            'thread_id' => function () {
                return \App\Models\Thread::factory()->create()->id;
            },
            'body' => $this->faker->paragraph(),
        ];
    }
}
