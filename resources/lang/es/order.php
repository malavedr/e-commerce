<?php

return [
    'created' => 'Orden creada exitosamente.',
    'failed' => 'Error al crear la orden.',
    'validation' => [
        'products' => [
            'required' => 'Debe seleccionar al menos un producto.',
            'sku_required' => 'Cada producto debe tener un SKU.',
            'sku_distinct' => 'El SKU debe ser único en todos los productos.',
            'sku_not_found' => 'El SKU especificado no existe en nuestro catálogo.',
            'quantity_required' => 'Cada producto debe tener una cantidad.',
            'quantity_min' => 'La cantidad debe ser al menos 1.',
        ],
    ],
    'errors' => [
        'no_active_delivery_address' => 'Debe tener una dirección de entrega activa para realizar un pedido.',
        'duplicate_order' => 'Ya ha realizado un pedido con los mismos productos.',
        'creation_failed' => 'Error al crear la orden debido a un error interno.',
    ],
];