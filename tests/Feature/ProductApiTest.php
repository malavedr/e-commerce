<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_accessible_by_active_admin(): void
    {
        Product::factory()->count(3)->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->getJson('/api/v1.0.0/products')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_index_accessible_by_active_editor(): void
    {
        Product::factory()->count(3)->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->getJson('/api/v1.0.0/products')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_index_accessible_by_active_user(): void
    {
        Product::factory()->count(3)->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->getJson('/api/v1.0.0/products')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_index_accessible_by_suspended_admin(): void
    {
        Product::factory()->count(3)->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->getJson('/api/v1.0.0/products')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_index_accessible_by_suspended_editor(): void
    {
        Product::factory()->count(3)->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->getJson('/api/v1.0.0/products')
            ->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_index_unaccessible_by_suspended_user(): void
    {
        Product::factory()->count(3)->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->getJson('/api/v1.0.0/products')
            ->assertForbidden();
    }

    public function test_show_product_by_active_admin(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->getJson("/api/v1.0.0/products/{$product->sku}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_show_product_by_active_editor(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->getJson("/api/v1.0.0/products/{$product->sku}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_show_product_by_active_user(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->getJson("/api/v1.0.0/products/{$product->sku}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_show_product_by_suspended_admin(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->getJson("/api/v1.0.0/products/{$product->sku}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_show_product_by_suspended_editor(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->getJson("/api/v1.0.0/products/{$product->sku}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_show_product_by_suspended_user(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->getJson("/api/v1.0.0/products/{$product->sku}")
            ->assertForbidden();
    }

    public function test_store_product_by_active_admin(): void
    {
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $payload = [
            'name' => 'Nuevo producto',
            'sku' => 'SKU-TEST-001',
            'price' => '123.45',
            'description' => 'Descripción de prueba',
        ];

        $this->postJson('/api/v1.0.0/products', $payload)
            ->assertOk()
            ->assertJsonPath('data.sku', 'SKU-TEST-001');
    }

    public function test_store_product_fails_by_active_editor(): void
    {
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $payload = [
            'name' => 'Nuevo producto',
            'sku' => 'SKU-TEST-001',
            'price' => '123.45',
            'description' => 'Descripción de prueba',
        ];

        $this->postJson('/api/v1.0.0/products', $payload)
            ->assertForbidden();
    }

    public function test_store_product_fails_by_active_user(): void
    {
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $payload = [
            'name' => 'Nuevo producto',
            'sku' => 'SKU-TEST-001',
            'price' => '123.45',
            'description' => 'Descripción de prueba',
        ];

        $this->postJson('/api/v1.0.0/products', $payload)
            ->assertForbidden();
    }

    public function test_store_product_by_suspended_admin(): void
    {
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $payload = [
            'name' => 'Nuevo producto',
            'sku' => 'SKU-TEST-001',
            'price' => '123.45',
            'description' => 'Descripción de prueba',
        ];

        $this->postJson('/api/v1.0.0/products', $payload)
            ->assertOk()
            ->assertJsonPath('data.sku', 'SKU-TEST-001');
    }

    public function test_store_product_fails_by_suspended_editor(): void
    {
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $payload = [
            'name' => 'Nuevo producto',
            'sku' => 'SKU-TEST-001',
            'price' => '123.45',
            'description' => 'Descripción de prueba',
        ];

        $this->postJson('/api/v1.0.0/products', $payload)
            ->assertForbidden();
    }

    public function test_store_product_fails_by_suspended_user(): void
    {
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $payload = [
            'name' => 'Nuevo producto',
            'sku' => 'SKU-TEST-001',
            'price' => '123.45',
            'description' => 'Descripción de prueba',
        ];

        $this->postJson('/api/v1.0.0/products', $payload)
            ->assertForbidden();
    }

    public function test_update_product_by_active_admin(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->putJson("/api/v1.0.0/products/{$product->sku}", [
            'name' => 'Producto actualizado',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'producto actualizado');
    }

    public function test_update_product_by_active_editor(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->putJson("/api/v1.0.0/products/{$product->sku}", [
            'name' => 'Producto actualizado',
        ])
        ->assertForbidden();
    }

    public function test_update_product_by_active_user(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->putJson("/api/v1.0.0/products/{$product->sku}", [
            'name' => 'Producto actualizado',
        ])
        ->assertForbidden();
    }

    public function test_update_product_by_suspended_admin(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->putJson("/api/v1.0.0/products/{$product->sku}", [
            'name' => 'Producto actualizado',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'producto actualizado');
    }

    public function test_update_product_by_suspended_editor(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->putJson("/api/v1.0.0/products/{$product->sku}", [
            'name' => 'Producto actualizado',
        ])
        ->assertForbidden();
    }

    public function test_update_product_by_suspended_user(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::SUSPENDED->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->putJson("/api/v1.0.0/products/{$product->sku}", [
            'name' => 'Producto actualizado',
        ])
        ->assertForbidden();
    }

    public function test_delete_product_by_active_admin(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        $this->deleteJson("/api/v1.0.0/products/{$product->sku}")
            ->assertOk()
            ->assertJson(['message' => 'The product has been successfully deleted.']);
    }

    public function test_delete_product_by_active_editor(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::EDITOR->value,
        ]);

        $this->deleteJson("/api/v1.0.0/products/{$product->sku}")
            ->assertForbidden();
    }

    public function test_delete_product_by_active_user(): void
    {
        $product = Product::factory()->create();
        $this->actingAsUser([
            'status' => UserStatusEnum::ACTIVE->value, 
            'role' => UserRoleEnum::USER->value,
        ]);

        $this->deleteJson("/api/v1.0.0/products/{$product->sku}")
            ->assertForbidden();
    }

    protected function actingAsUser(array $data): User
    {
        $user = User::factory()
            ->withPhoneContact()
            ->withActiveDeliveryAddresses()
            ->create([
                'role' => $data['role'],
                'status' => $data['status'],
            ]);

        Sanctum::actingAs($user);

        return $user;
    }
}
