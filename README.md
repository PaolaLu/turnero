# Turnero

Sistema de reserva de turnos para pequeños negocios (peluquerías, consultorios, estudios, etc.).
Cada negocio tiene **su propia instalación independiente** (single-tenant).

## Stack

- Laravel 12 · PHP 8.2+ · MySQL/MariaDB
- Blade + Livewire 3 (Alpine.js incluido en Livewire)
- Tailwind CSS compilado con Vite **en local** (a producción se sube `public/build`)
- Tests con Pest · estilo de código con Laravel Pint
- Producción: Hostinger Business (hosting compartido, sin root). Ver `REGLAS_DESARROLLO.md` del proyecto.

## Requisitos en Laragon (Windows)

Antes de la primera instalación, revisar:

- **Usar la terminal de Laragon** (botón *Terminal*). Pone primero en el PATH el PHP, git, mysql y node
  de Laragon. Si en Windows hay otro PHP (por ejemplo XAMPP), una terminal común puede tomar ese.
- **Habilitar la extensión `zip` de PHP**: en Laragon, *Menú > PHP > Extensions > zip*, o descomentar
  `extension=zip` en el `php.ini` de la versión de PHP en uso. Sin `zip`, `composer install` intenta
  clonar cada paquete con git y puede cortarse por timeout.
- **Arrancar los servicios con *Start All*** (Apache/Nginx + MySQL). No arrancar `mysqld` a mano.
- **Instalar dependencias de Node con `npm ci`**, no con `npm install`. `npm ci` respeta
  `package-lock.json` tal cual; `npm install` puede reescribirlo si la versión de npm es distinta.

## Puesta en marcha local (Laragon en Windows)

1. Clonar el repo dentro de `C:\laragon\www\turnero`.
2. Crear la base `turnero` en HeidiSQL (utf8mb4).
3. En la carpeta del proyecto:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm ci
npm run build
```

4. Abrir `http://turnero.test` (Laragon crea el dominio automáticamente al reiniciar).

## Comandos útiles

| Acción | Comando |
| --- | --- |
| Correr tests | `php artisan test` |
| Revisar estilo | `vendor/bin/pint --test` |
| Compilar assets | `npm run build` |
| Assets en vivo mientras se desarrolla | `npm run dev` |

## Documentación

Las decisiones técnicas están en [`docs/`](docs/).
