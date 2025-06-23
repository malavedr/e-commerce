<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(25)
            ->withPhoneContact(2)
            ->withActiveDeliveryAddresses(1)
            ->withInactiveDeliveryAddresses(2)
            ->create();
    }
}
