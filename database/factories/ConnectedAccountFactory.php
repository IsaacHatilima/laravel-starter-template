<?php

namespace Database\Factories;

use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ConnectedAccountFactory extends Factory
{
    protected $model = ConnectedAccount::class;

    public function definition(): array
    {
        $user = User::factory();
        return [
            'identifier' => $user->email,
            'service' => $this->faker->randomElement(['google', 'github', 'apple']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => $user,
        ];
    }
}
