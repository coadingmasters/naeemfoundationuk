# Naeem Foundation

A web application built with [Laravel](https://laravel.com).

## Requirements

- PHP 8.3+
- Composer
- Node.js & npm

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
```

## Running locally

```bash
composer run dev
```

This starts the PHP server, queue worker, log viewer, and Vite dev server together.

## Testing

```bash
php artisan test
```
