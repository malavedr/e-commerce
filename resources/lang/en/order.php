<?php

return [
    'created' => 'Order created successfully.',
    'failed' => 'Failed to create the order.',
    'validation' => [
        'products' => [
            'required' => 'You must select at least one product.',
            'sku_required' => 'Each product must have an SKU.',
            'sku_distinct' => 'The SKU must be unique across all products.',
            'sku_not_found' => 'The specified SKU does not exist in our catalog.',
            'quantity_required' => 'Each product must have a quantity.',
            'quantity_min' => 'Quantity must be at least 1.',
        ],
    ],
    'errors' => [
        'no_active_delivery_address' => 'You must have an active delivery address to place an order.',
        'duplicate_order' => 'You have already placed an order with the same products.',
        'creation_failed' => 'Failed to create the order due to an internal error.',
    ],
];