#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
APP_DIR="$PROJECT_DIR/GlassTasksApp"

if ! command -v composer >/dev/null 2>&1; then
  echo "Composer not found. Please install Composer first." >&2
  exit 1
fi

composer create-project laravel/laravel "$APP_DIR" "^11.0"
pushd "$APP_DIR" >/dev/null

composer require laravel/sail --dev
php artisan sail:install --no-interaction --with=mysql,redis

composer require \
  laravel/breeze:^2.0 \
  laravel/reverb \
  laravel/horizon \
  laravel/sanctum \
  laravel/scout:^10 \
  spatie/laravel-activitylog:^4 \
  spatie/icalendar-generator:^0.8 \
  spatie/laravel-permission:^6 \
  spatie/laravel-query-builder:^6 \
  minishlink/web-push:^9.3 \
  laravel-notification-channels/webpush:^9.0

php artisan breeze:install react --typescript

composer require pestphp/pest --dev
php artisan pest:install
composer require nunomaduro/collision --dev laravel/pint --dev phpstan/phpstan --dev friendsofphp/php-cs-fixer --dev

npm install
npm install -D tailwindcss postcss autoprefixer @types/node @vitejs/plugin-react eslint prettier eslint-config-prettier eslint-plugin-react eslint-plugin-react-hooks @tanstack/react-query framer-motion @fullcalendar/core @fullcalendar/react @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction
npm install -D playwright @playwright/test

popd >/dev/null

# Copy stubs
rsync -a "$PROJECT_DIR/stubs/" "$APP_DIR/"

# Update timezone
sed -i 's/APP_TIMEZONE=.*/APP_TIMEZONE=Europe\/London/' "$APP_DIR/.env.example"

echo "Setup complete. Next steps:\n  cd $APP_DIR\n  cp .env.example .env && php artisan key:generate\n  php artisan migrate --seed\n  npm run dev\n  php artisan horizon & php artisan reverb:start & php artisan serve"