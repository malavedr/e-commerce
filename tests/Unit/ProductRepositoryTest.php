<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contracts\ProductRepositoryInterface;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_paginate_active_returns_only_active_products()
    {
        // Arrange: crear productos activos e inactivos
        Product::factory()->count(3)->active()->create();
        Product::factory()->count(2)->inactive()->create();

        // Act: obtener productos activos desde el repositorio
        $repository = app(ProductRepositoryInterface::class);
        $result = $repository->paginateActive();

        // Assert: solo hay productos activos
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn ($product) => $product->is_active));
    }
}