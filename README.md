# Laravel, Inertia, React, ShadCN, Tailwind Starter Kit

## What changed?

Nothing much, just added custom components to have input, label and error in one file. Fewer places to change. The
starter
template also includes the introduction of Repositories and DTOs.

## Added packages

### Static Analysis and Code Style

- [PHPStan](https://phpstan.org/)  
  Static analysis for PHP. Development only.

- [PHP Insights](https://github.com/nunomaduro/phpinsights)  
  Code quality and architecture analysis. Development only.

## Documentation

- [Laravel](https://laravel.com/)
- [Inertia.js](https://inertiajs.com/)
- [React](https://react.dev/)
- [PHPStan](https://phpstan.org/)
- [PHP Insights](https://github.com/nunomaduro/phpinsights)
- [Laravel Pint](https://laravel.com/docs/12.x/pint)
- [shadcn/ui](https://ui.shadcn.com/)
- [Laravel Fortify](https://laravel.com/docs/12.x/fortify#main-content)

## Getting Started

```bash

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment config and generate app key
cp .env.example .env
php artisan key:generate

# Run local development servers
php artisan serve
npm run dev
```
