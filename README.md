# Bestiario Borealis Admin

Panel administrativo Laravel para gestionar fichas de criaturas, personajes, PNJ, jefes y entidades fantasticas.

## Stack

- Laravel 13, PHP 8.3+
- Blade, Breeze, Tailwind CSS, Alpine
- Sanctum para API REST protegida
- spatie/laravel-permission para roles `admin`, `editor`, `viewer`
- Storage public para imagenes y assets
- DeepSeek Chat configurable por `.env`

## Instalacion

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configura la base real en `.env`. El proyecto queda listo para MySQL o PostgreSQL; no necesita una base local instalada para preparar el codigo.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bestiario_borealis
DB_USERNAME=usuario_real
DB_PASSWORD=password_real

DEEPSEEK_API_KEY=sk-real
DEEPSEEK_MODEL=deepseek-chat
```

Cuando la base exista:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

Usuario demo sembrado:

- Email: `admin@bestiarioborealis.test`
- Password: `password`

## Desarrollo

```bash
composer run dev
```

Rutas principales:

- `/dashboard`
- `/entries`
- `/themes`
- `/import-json`
- `/generate-creature`
- `/bestiary/{slug}` para fichas publicadas

## API protegida

Todas las rutas usan `auth:sanctum`.

Base esperada en produccion:

`https://bestiarioborealis.lat/bestiario/api`

- `GET /api/entries`
- `POST /api/entries`
- `GET /api/entries/{id}`
- `PUT /api/entries/{id}`
- `DELETE /api/entries/{id}`
- `GET /api/themes`
- `POST /api/import-json`
- `POST /api/generate-creature`
- `GET /api/entries/{id}/export-json`

## API movil y sincronizacion

El manual pegable para el equipo del app movil esta en:

`docs/mobile-sync-api-prompt.md`

Incluye registro/login por token, verificacion por `account_id + creature_uid`, diff de criaturas locales/remotas y upsert desde el dispositivo.

## Tests

```bash
php artisan test
```

Los tests usan SQLite en memoria y el generador IA responde con payload fake en `APP_ENV=testing`, sin llamar a DeepSeek.
