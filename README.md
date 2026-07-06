# Sistema de Grúas - Asistencia Vial

Sistema web para la administración y gestión de servicios de grúas y asistencia vial. Desarrollado con Laravel.

## Tecnologías

- **Backend:** Laravel 11
- **Base de datos:** SQLite
- **Frontend:** Blade, Tailwind CSS, JavaScript

## Requisitos

- PHP 8.2+
- Composer
- Node.js & npm

## Instalación

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Credenciales de acceso

| Rol | Email | Contraseña |
|------|-------|-----------|
| Administrador | admin@gruas.com | password |
| Cotizador | cotizador@gruas.com | password |
| Operador | luis@gruas.com | password |
| Operador | maria@gruas.com | password |
| Cliente | cliente@gruas.com | password |
