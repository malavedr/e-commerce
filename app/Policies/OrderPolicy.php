<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use App\Enums\UserRoleEnum;

/**
 * Class OrderPolicy
 *
 * Defines authorization logic for actions related to orders.
 * Grants permissions based on user roles and ownership.
 *
 * @package App\Policies
 */
class OrderPolicy
{
    /**
     * Determine whether the user can view any orders.
     *
     * Only admin and editor roles are allowed to view the list of orders.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]);
    }

    /**
     * Determine whether the user can view a specific order.
     *
     * Admins, editors, and the active buyer who owns the order are allowed.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return bool
     */
    public function view(User $user, Order $order): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]) || ($user->isActive() && $user->isOwner($order->buyer_id));
    }

    /**
     * Determine whether the user can create a new order.
     *
     * Any active user is allowed to place an order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isActive();
    }

    /**
     * Determine whether the user can update a given order.
     *
     * Only the active buyer who owns the order is allowed to update it.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return bool
     */
    public function update(User $user, Order $order): bool
    {
        return ($user->isActive() && $user->isOwner($order->buyer_id));
    }

    /**
     * Determine whether the user can delete a given order.
     *
     * Admins or the active buyer who owns the order are allowed to delete it.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return bool
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]) || ($user->isActive() && $user->isOwner($order->buyer_id));
    }
}
