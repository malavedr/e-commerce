<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserStaticSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Diego Admin Active',
                'email' => 'diego.admin.active@e-commerce.com',
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => 'Diego Admin Suspended',
                'email' => 'diego.admin.suspended@e-commerce.com',
                'role' => 'admin',
                'status' => 'suspended',
            ],
            [
                'name' => 'Diego Editor Active',
                'email' => 'diego.editor.active@e-commerce.com',
                'role' => 'editor',
                'status' => 'active',
            ],
            [
                'name' => 'Diego Editor Suspended',
                'email' => 'diego.editor.suspended@e-commerce.com',
                'role' => 'editor',
                'status' => 'suspended',
            ],
            [
                'name' => 'Diego User Active',
                'email' => 'diego.user.active@e-commerce.com',
                'role' => 'user',
                'status' => 'active',
            ],
            [
                'name' => 'Diego User Suspended',
                'email' => 'diego.user.suspended@e-commerce.com',
                'role' => 'user',
                'status' => 'suspended',
            ]
        ];

        $tokens = [];
        foreach ($users as $info) {
            $factory = User::factory()
                ->withPhoneContact()
                ->withActiveDeliveryAddresses(1);

            $factory = $info['status'] === 'active'
                ? $factory->active()
                : $factory->suspended();

            $factory = match ($info['role']) {
                'admin' => $factory->admin(),
                'editor' => $factory->editor(),
                default => $factory->user(),
            };

            $user = $factory->create([
                'name' => $info['name'],
                'email' => $info['email'],
                'password' => Hash::make('password'),
            ]);

            $token = $user->createToken('seeder-token')->plainTextToken;
            $tokens[$info['email']] = $token;
        }
        
        error_log(print_r($tokens, true));
    }
}
