# OBJETIVOS
- Poder crear una orden por api
- Poder ver el detalle de una orden

# PUNTOS EXTRAS
- Documentar api con open api                                               PENDIENTE
- Utilizar redis en alguna parte del proceso para evitar accesos a la db    LISTO
- Enviar email por medio de jobs                                            PENDIENTE
- Utiliza Policies, Form Request, Traits, Service etc                       LISTO
- Crear test unitarios                                                      LISTO
- Implementar autenticacion                                                 LISTO
- Implementar laravel permission                                            LISTO

# PENDIENTES
- limpiar el resource de order
- Documentar en open api
- enviar mail al crear una orden
- implementar redis en otro sitio
- aplicar test a orders y products
- validar bien el policy de orders

# TERMINADOS
- Crear orden por api
- Poder ver el detalle de una orden
- Cambiar id de producto por sku
- Crear autenticación por api
- Proceso completo de productos
- Crear Policies a productos
- Implementar roles simples
- probar los permisos sobre productos y ahora si terminar con policies
- Armar seeders con usuarios en concreto con roles en concreto
- Implementar autenticacion
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