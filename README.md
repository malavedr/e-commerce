# Proyecto Laravel 12: E-commerce

Este proyecto utiliza **Laravel 12** y hace uso de migraciones, seeders y colas para la gestión de datos y tareas en segundo plano.

## Requisitos

- PHP >= 8.2
- Composer
- SQLite
- Redis (para colas)

## Instalación

```bash
git clone https://github.com/tu-usuario/e-commerce.git
cd e-commerce
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu base de datos en el archivo `.env`.

## Migraciones y Seeders

Ejecuta las migraciones y seeders para preparar la base de datos:

```bash
php artisan migrate --seed
```

## Uso de Colas

 Es necesario ejecutar el procesamiento de colas al momento de crear una orden para que el sistema pueda enviar el email de confirmación correspondiente.

```bash
php artisan queue:work
```

## La documentación de la API se encuentra disponible en el endpoint /api/documentation

## Usuarios de Prueba

Se crearon varios usuarios con diferentes roles y status para facilitar las pruebas del sistema. Puedes utilizar estos usuarios para verificar los distintos permisos y funcionalidades según el rol y el estado asignado.

| Email                                 | Status           | Rol           | Password   |
|---------------------------------------|------------------|---------------|------------|
| diego.admin.active@e-commerce.com     | Activa           | Administrador | password   |
| diego.admin.suspended@e-commerce.com  | Suspendida       | Administrador | password   |
| diego.editor.active@e-commerce.com    | Activa           | Editor        | password   |
| diego.editor.suspended@e-commerce.com | Suspendida       | Editor        | password   |
| diego.user.active@e-commerce.com      | Activa           | User          | password   |
| diego.user.suspended@e-commerce.com   | Suspendida       | User          | password   |

Además de la creación de los usuarios, se genera automáticamente un **token de acceso** para cada uno. Este token puede ser utilizado para autenticación en pruebas de API o integraciones. Puedes encontrar el token correspondiente en la base de datos, en la tabla `personal_access_tokens`.

## Productos de Prueba

El sistema incluye productos predefinidos para facilitar las pruebas:

- Los productos con SKU desde **SKU-0001** hasta **SKU-0005** están **activos**.
- Los productos con SKU desde **SKU-0006** hasta **SKU-0010** están **inactivos**.

## Tests

Para ejecutar los tests del proyecto:

```bash
php artisan test
```

**Nota:** Por falta de tiempo, solo se implementaron algunos tests básicos para el modelo `Product`.

## Comandos útiles
- Ejecutar migraciones: `php artisan migrate`
- Ejecutar seeders: `php artisan db:seed`
- Limpiar y volver a migrar: `php artisan migrate:fresh --seed`
- Procesar colas: `php artisan queue:work`
