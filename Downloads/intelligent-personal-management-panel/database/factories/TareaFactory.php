<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TareaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'prioridad' => fake()->randomElement(['baja', 'media', 'alta']),
            'estado' => fake()->randomElement(['pendiente', 'en_progreso', 'completada']),
            'fecha_limite' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
