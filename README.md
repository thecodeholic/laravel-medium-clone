<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Medium Clone

A Medium-like blogging platform built with Laravel 12, featuring article publishing, user authentication, and media
management.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL

## Setup Instructions

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/laravel-medium-clone.git
   cd laravel-medium-clone
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

### Database Setup

The project is configured to use SQLite by default:

1. First, ensure your file has the correct database configuration: `.env`

```bash
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```

**Note:** You can remove other DB_* variables (DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD) as they're not needed for
SQLite.

2. Create the SQLite database file:
   ```bash
   touch database/database.sqlite
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

4. (Optional) Seed the database with sample data:
   ```bash
   php artisan db:seed
   ```

If you want to start fresh at any point, you can use:

```bash
 php artisan migrate:fresh
```

This will drop all tables and re-run all migrations. Add `--seed` flag if you want to reseed the database:

```bash
php artisan migrate:fresh --seed
```

### Running the Application

You can run the application using the custom dev script that starts the Laravel server, queue worker, and Vite
development server concurrently:

```bash
 composer dev
```

Or run each service separately:

1. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

2. Start the queue worker:
   ```bash
   php artisan queue:listen
   ```

3. Compile assets with Vite:
   ```bash
   npm run dev
   ```

The application will be available at http://localhost:8000

### Testing

This project uses PestPHP for testing. To run the tests:

```bash
php artisan test
```

Or to run tests with Pest directly:

```bash
./vendor/bin/pest
```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all
modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a
modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video
tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging
into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in
becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in
the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by
the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell
via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
