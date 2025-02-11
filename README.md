# Bitgrid
Laravel CMS

Online cms software designed for small businesses and freelancers. Bitgrid is built with modern technologies such as Laravel, Vite.js, Alpine.js, Tailwind, RESTful API etc. Thanks to its modular structure, Bitgrid provides an awesome App Store for users and developers.

## Requirements

* PHP 8.2 or higher
* Database (e.g.: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)

## Framework

Bitgrid uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and [Module](https://github.com/nWidart/laravel-modules) package for Apps.

## Installation

* Install [Composer](https://getcomposer.org/download) and [Npm](https://nodejs.org/en/download)
* Clone the repository: `git clone https://github.com/nakornsoft/bitgrid.git`
* Install dependencies: `composer install ; npm install ; npm run dev`
* Install Bitgrid:

```bash
php artisan migrate
php artisan module:publish
```

* Create sample data (optional): `php artisan  db:seed --class="SampleSeeder"`

## Docker

[Docker File](DOCKER.md)

# Passport API
```bash
php artisan passport:install
php artisan passport:client --personal
```

# Deployment
```bash
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

sudo chmod -R 775 storage
sudo chmod -R ugo+rw storage
```

## Changelog

Please see [Releases](../../releases) for more information about what has changed recently.

## Security

Please review [our security policy](https://github.com/nakornsoft/bitgrid/security/policy) on how to report security vulnerabilities.

## Credits

* [Nakornsoft](https://github.com/nakornsoft)

## License

Bitgrid is released under the [GNU GENERAL PUBLIC License](LICENSE.txt).
