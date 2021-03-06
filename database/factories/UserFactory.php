<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $email = $this->faker->email();
        return [
            'role_id' => Role::operator()?->id ?? 2,
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $email,
            'password' => Hash::make($email)
        ];
    }
}
