<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'title' => $this->faker->sentence(),
                'body' => $this->faker->paragraph($nb = 50, $asText = false),
                
                'files' => json_encode(["key" => $this->faker->image('storage/app/public/images',400,300, null, false)]),
                'locked' => false,
                'archived' => false,
                'author_id' => function () {
                    return \App\Models\User::factory()->create()->id;
                },
                'category_id' => function () {
                    return \App\Models\Category::factory()->create()->id;
                }  
        ];
    }
}
