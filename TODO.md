# OBJETIVOS
- Poder crear una orden por api
- Poder ver el detalle de una orden

# PUNTOS EXTRAS
- Documentar api con open api
- Utilizar redis en alguna parte del proceso para evitar accesos a la db
- Enviar email por medio de jobs
- Utiliza Policies, Form Request, Traits, Service etc
- Crear test unitarios
- Implementar autenticacion
- Implementar laravel permission

# PENDIENTES
- Proceso completo de productos (Falta revizar todo)
- no utilizar 'exception' => $e->getMessage(), en los exception del repositorio
- ver que hago con ApiException
- Crear rutas
- Crear controladores
- Crear form request
- Crear Policies
- Crear autenticación por api
- 

# TERMINADOS
- Crear factories
- Crear Seeder de modelo user
- Crear scopres de status, types etc ejemplo: scopeActive($query){return $query->where('status', true);}
- Crear Seeders
- Crear Modelos con todo y relaciones
- Crear modelo entidad relación
- Crear migraciones



# MODELAR
Ordenes de venta
- Comprador
- Domicilio de entrega
- Datos de facturacion
- Datos de productos vendidos en la orden


# Ramas
- 