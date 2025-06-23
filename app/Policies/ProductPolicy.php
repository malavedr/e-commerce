<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use App\Enums\UserRoleEnum;

/**
 * Class ProductPolicy
 *
 * Defines authorization rules for actions performed on Product models.
 * Determines which users can view, create, update, or delete products based on roles or status.
 *
 * @package App\Policies
 */
class ProductPolicy
{
    /**
     * Determine whether the user can view any product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]) || $user->isActive();
    }

    /**
     * Determine whether the user can view the specific product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function view(User $user, Product $product): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value, UserRoleEnum::EDITOR->value]) || $user->isActive();
    }

    /**
     * Determine whether the user can create a product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]);
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]);
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return bool
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole([UserRoleEnum::ADMIN->value]);
    }
}
