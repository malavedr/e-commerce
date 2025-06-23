<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Seeder;

class UserStaticSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses(1)
            ->active()
            ->admin()
            ->create([
                'name' => 'Diego Admin Active',
                'email' => 'diego.admin.active@e-commerce.com',
            ]);

        User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses(1)
            ->suspended()
            ->admin()
            ->create([
                'name' => 'Diego Admin Suspended',
                'email' => 'diego.admin.suspended@e-commerce.com',
            ]);

        User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses(1)
            ->active()
            ->editor()
            ->create([
                'name' => 'Diego Editor Active',
                'email' => 'diego.editor.active@e-commerce.com',
            ]);

        User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses(1)
            ->suspended()
            ->editor()
            ->create([
                'name' => 'Diego Editor Suspended',
                'email' => 'diego.editor.suspended@e-commerce.com',
            ]);

        User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses(1)
            ->active()
            ->user()
            ->create([
                'name' => 'Diego User Active',
                'email' => 'diego.user.active@e-commerce.com',
            ]);

        User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses(1)
            ->suspended()
            ->user()
            ->create([
                'name' => 'Diego User Suspended',
                'email' => 'diego.user.suspended@e-commerce.com',
            ]);
    }
}
