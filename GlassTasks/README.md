# GlassTasks

A modern glassmorphism task manager built with Laravel 11, Inertia React TS, Tailwind, Redis queues, WebSockets (Reverb), Web Push (PWA), FullCalendar, and Horizon.

## Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ and npm
- Redis (for queues, cache)
- MySQL 8+ (or SQLite for local dev)
- Optional: Docker (Laravel Sail)

## Quickstart

```bash
# 1) Setup
make setup

# 2) Configure environment
cp GlassTasksApp/.env.example GlassTasksApp/.env
# Fill DB_*, REDIS_*, REVERB_*, MAIL_*, VAPID_* (see .env example below)

# 3) Generate key
cd GlassTasksApp && php artisan key:generate

# 4) Install JS deps and build dev assets
npm --prefix GlassTasksApp install
npm --prefix GlassTasksApp run dev

# 5) Migrate and seed sample data
php -d detect_unicode=0 -d allow_url_fopen=1 -d detect_unicode=0 GlassTasksApp/artisan migrate --force
php GlassTasksApp/artisan db:seed --force

# 6) Start queues, horizon, and websockets
php GlassTasksApp/artisan horizon &
php GlassTasksApp/artisan reverb:start &
php GlassTasksApp/artisan serve
```

### Using Sail (Docker)
```bash
make sail-up
```
This brings up PHP-FPM, MySQL, Redis, and Node builder. Use `make sail-dev` for dev asset building.

## Temporary Live Deployment
- Render: uses `render.yaml` with separate web service and workers (horizon, websockets) and cron for scheduler.
- Railway: `railway.json` sets up MySQL and Redis along with the web service.
- Ngrok (quick demo):
  - `php GlassTasksApp/artisan serve` (e.g., on 8000)
  - `ngrok http 8000`

## Environment (.env) example
```
APP_NAME=GlassTasks
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=Europe/London

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=glasstasks
DB_USERNAME=root
DB_PASSWORD=

# For local, you can use SQLite instead:
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/GlassTasksApp/database/database.sqlite

BROADCAST_DRIVER=reverb
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

REVERB_APP_ID=local
REVERB_APP_KEY=local
REVERB_APP_SECRET=local
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

MAIL_MAILER=log
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="GlassTasks"

VAPID_PUBLIC_KEY=
VAPID_PRIVATE_KEY=

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:5173
SESSION_DOMAIN=localhost
```

## Features
- Projects with membership and invites
- Tasks with status, priority, due/start, assignee, tags, reminders
- Subtasks and comments
- Real-time updates via Reverb, toasts and bell dropdown
- Email notifications and daily digests
- Web Push reminders (PWA) with install prompt
- RRULE recurring tasks; auto-spawn next instance
- Kanban board with drag-and-drop
- Calendar (FullCalendar) month/week/day with drag-reschedule
- Search and filters (project, status, priority, tag, date range, assignee)
- Import CSV and ICS export
- Access control with Policies and Gates
- Activity log for tasks

## Make targets
- `make setup`: Bootstrap Laravel app, install packages, copy stubs, run initial build
- `make seed`: Seed sample data
- `make qa`: Pint, PHPStan, Pest
- `make dev`: Run Vite dev and PHP server
- `make sail-up`: Bring up Docker composition (Sail)

## CI
GitHub Actions workflow at `.github/workflows/ci.yml` runs PHP and JS tests, lint, and build.

## PWA Tutorial
- When you first visit the dashboard, you’ll see a prompt to enable notifications and install the PWA.
- Allow notifications to receive Web Push reminders based on each task’s smart offsets (e.g., 1d, 1h, 10m before due).
- Use the bell icon to manage permissions and subscriptions. Offline shell caches the dashboard and calendar for quick access.

## License
MIT