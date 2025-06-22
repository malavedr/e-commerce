<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Collection;
use App\Contracts\ProductRepositoryInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;


class ProductControllerTest extends TestCase
{
    public function test_index_returns_products_from_repository()
    {
        $products = Product::factory()->count(2)->make();

        $this->mock(ProductRepositoryInterface::class, function ($mock) use ($products) {
            $paginator = new LengthAwarePaginator(
                $products, // items
                $products->count(), // total
                15, // per page
                1 // current page
            );

            $mock->shouldReceive('paginateActive')
                ->once()
                ->andReturn($paginator);
        });

        // Act: hacer la petición
        $response = $this->getJson('/api/v1.0.0/products', [
            'Accept' => 'application/json',
        ]);

        // Assert: verificar respuesta
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => $products[0]->name]);
    }
}
