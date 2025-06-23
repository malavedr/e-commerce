<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductStaticSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->active()->create(['sku' => 'SKU-0001']);
        Product::factory()->active()->create(['sku' => 'SKU-0002']);
        Product::factory()->active()->create(['sku' => 'SKU-0003']);
        Product::factory()->active()->create(['sku' => 'SKU-0004']);
        Product::factory()->active()->create(['sku' => 'SKU-0005']);
        Product::factory()->inactive()->create(['sku' => 'SKU-0006']);
        Product::factory()->inactive()->create(['sku' => 'SKU-0007']);
        Product::factory()->inactive()->create(['sku' => 'SKU-0008']);
        Product::factory()->inactive()->create(['sku' => 'SKU-0009']);
        Product::factory()->inactive()->create(['sku' => 'SKU-0010']);
    }
}
