<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use App\Enums\UserRoleEnum;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]);
    }

    public function view(User $user, Order $order): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]) || ($user->isActive() && $user->isOwner($order->user_id));
    }

    public function create(User $user): bool
    {
        return $user->isActive();
    }

    public function update(User $user, Order $order): bool
    {
        return ($user->isActive() && $user->isOwner($order->user_id));
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]) || ($user->isActive() && $user->isOwner($order->user_id));
    }
}
