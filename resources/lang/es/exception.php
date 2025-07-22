<?php

return [

    'products' => [
        'not_found' => 'El producto solicitado no existe.',
        'unauthorized' => 'No tiene permisos para acceder a este producto.',
        'creation_failed' => 'No se pudo crear el producto. Inténtelo de nuevo más tarde.',
        'update_failed' => 'Error al actualizar el producto. Inténtelo de nuevo más tarde.',
        'deletion_failed' => 'No se pudo eliminar el producto. Verifique los datos e inténtelo de nuevo.',
        'has_orders' => 'El producto no se puede eliminar porque tiene órdenes asociadas.',
    ],

    'orders' => [
        'not_found' => 'La orden no existe o no está disponible.',
        'unauthorized' => 'No está autorizado para ver esta orden.',
        'creation_failed' => 'Hubo un error al procesar la orden. Verifique los datos.',
    ],

    'general' => [
        'not_found' => 'El recurso solicitado no fue encontrado.',
        'unauthorized' => 'Acceso no autorizado.',
        'internal_error' => 'Ocurrió un error inesperado en el servidor.',
    ],
];