<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use App\Enums\UserRoleEnum;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]) || $user->isActive();
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]) || $user->isActive();
    }

    public function create(User $user): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]);
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]);
    }
}
