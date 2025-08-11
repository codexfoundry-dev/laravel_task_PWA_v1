SHELL := /usr/bin/bash

PROJECT_DIR := $(PWD)
APP_DIR := $(PROJECT_DIR)/GlassTasksApp
PHP := php
COMPOSER := composer
NPM := npm

setup:
	@if ! command -v $(COMPOSER) >/dev/null 2>&1; then echo "Composer not found. Please install Composer." && exit 1; fi
	$(COMPOSER) create-project laravel/laravel "$(APP_DIR)" "^11.0"
	cd $(APP_DIR) && $(COMPOSER) require laravel/sail --dev && php artisan sail:install --no-interaction --with=mysql,redis
	cd $(APP_DIR) && $(COMPOSER) require laravel/breeze:^2.0 laravel/reverb laravel/horizon spatie/laravel-activitylog:^4 spatie/icalendar-generator:^0.8 spatie/laravel-permission:^6 spatie/laravel-query-builder:^6 spatie/laravel-ignition --with-all-dependencies
	cd $(APP_DIR) && php artisan breeze:install react --typescript
	cd $(APP_DIR) && $(COMPOSER) require laravel/sanctum laravel/scout:^10
	cd $(APP_DIR) && $(COMPOSER) require laravel-notification-channels/webpush:"^9.0" minishlink/web-push:"^9.3"
	cd $(APP_DIR) && $(COMPOSER) require pestphp/pest --dev && php artisan pest:install
	cd $(APP_DIR) && $(COMPOSER) require nunomaduro/collision --dev laravel/pint --dev phpstan/phpstan --dev friendsofphp/php-cs-fixer --dev
	cd $(APP_DIR) && $(NPM) install
	cd $(APP_DIR) && $(NPM) install -D tailwindcss postcss autoprefixer @types/node @vitejs/plugin-react eslint prettier eslint-config-prettier eslint-plugin-react eslint-plugin-react-hooks @tanstack/react-query framer-motion @fullcalendar/core @fullcalendar/react @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/interaction
	cd $(APP_DIR) && $(NPM) install -D playwright @playwright/test
	# copy stubs into app
	cp -r $(PROJECT_DIR)/stubs/app $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/config $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/database $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/resources $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/routes $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/tests $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/public $(APP_DIR)/
	cp -r $(PROJECT_DIR)/stubs/.github $(APP_DIR)/
	cp $(PROJECT_DIR)/stubs/.eslintrc.cjs $(APP_DIR)/.eslintrc.cjs || true
	cp $(PROJECT_DIR)/stubs/.prettierrc $(APP_DIR)/.prettierrc || true
	cp $(PROJECT_DIR)/stubs/tailwind.config.cjs $(APP_DIR)/tailwind.config.cjs
	cp $(PROJECT_DIR)/stubs/postcss.config.cjs $(APP_DIR)/postcss.config.cjs
	cp $(PROJECT_DIR)/stubs/vite.config.ts $(APP_DIR)/vite.config.ts
	cp $(PROJECT_DIR)/stubs/render.yaml $(APP_DIR)/render.yaml
	cp $(PROJECT_DIR)/stubs/railway.json $(APP_DIR)/railway.json
	# set timezone
	sed -i 's/APP_TIMEZONE=.*/APP_TIMEZONE=Europe\/London/' $(APP_DIR)/.env.example

seed:
	cd $(APP_DIR) && $(PHP) artisan migrate --force && $(PHP) artisan db:seed --force

qa:
	cd $(APP_DIR) && ./vendor/bin/pint -v
	cd $(APP_DIR) && ./vendor/bin/phpstan analyse --memory-limit=1G || true
	cd $(APP_DIR) && ./vendor/bin/pest -p

dev:
	cd $(APP_DIR) && $(NPM) run dev &
	cd $(APP_DIR) && $(PHP) artisan serve

sail-up:
	cd $(APP_DIR) && ./vendor/bin/sail up -d

.PHONY: setup seed qa dev sail-up