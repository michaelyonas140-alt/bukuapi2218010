<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'year' => $this->faker->numberBetween(1900, 2023),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
