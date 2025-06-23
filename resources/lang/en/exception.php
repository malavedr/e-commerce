<?php

return [

    'products' => [
        'not_found' => 'The requested product does not exist.',
        'unauthorized' => 'You do not have permission to access this product.',
        'creation_failed' => 'The product could not be created. Please try again later.',
        'update_failed' => 'Failed to update the product. Please try again later.',
        'deletion_failed' => 'The product could not be deleted. Please check the data and try again.',
        'has_orders' => 'The product cannot be deleted because it has associated orders.',
    ],

    'orders' => [
        'not_found' => 'The order does not exist or is not available.',
        'unauthorized' => 'You are not authorized to view this order.',
        'creation_failed' => 'There was an error processing the order. Please check the data.',
    ],

    'general' => [
        'not_found' => 'The requested resource was not found.',
        'unauthorized' => 'Unauthorized access.',
        'internal_error' => 'An unexpected server error occurred.',
    ],
];
