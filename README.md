# laravel-admin-boilerplate
An enterprise-grade Laravel 10 boilerplate built for high-performance admin panel development. This starter kit provides a clean and scalable architecture with built-in authentication, API support, and modular structure for rapid application development.

# Admin login
# Audit logs
# Error logs
# By Default error store no need try catch
# Dynamic Response function
# Common view path
# forgot password
# sms config
# email config
# file system
# file manager
# custom field
# Role management
# admin settings

## Run with Docker

1. Create your environment file if you do not already have one:
```bash
cp .env.example .env
```

2. Optional: add container DB credentials to `.env` (or export in shell):
```dotenv
MYSQL_DATABASE=laravel
MYSQL_USER=laravel
MYSQL_PASSWORD=laravel
MYSQL_ROOT_PASSWORD=root
APP_PORT=8000
```

3. Build and start:
```bash
docker compose build
docker compose up -d
```

4. One-time app setup:
```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

5. Open the app:
```text
http://localhost:8000
```

If port `8000` is already in use, set a different `APP_PORT` in `.env` (for example `APP_PORT=8085`) and run `docker compose up -d` again.
