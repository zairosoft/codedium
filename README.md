# [Bitgrid](https://github.com/nakornsoft/bitgrid)

> Laravel CMS

Online cms software designed for small businesses and freelancers. Bitgrid is built with modern technologies such as Laravel, Vite.js, Alpine.js, Tailwind, RESTful API etc. Thanks to its modular structure, Bitgrid provides an awesome App Store for users and developers.

![Screen](https://www.nakornsoft.com/wp-content/uploads/2025/02/bitgrid.png "Dashboards")

[English](README.md)
[ภาษาไทย](README-TH.md)

## Requirements

* PHP 8.2 or higher
* Database (e.g.: MySQL, PostgreSQL, SQLite)
* Redis 5.0.14.1 or higher | [Windows](https://github.com/tporadowski/redis/releases/tag/v5.0.14.1) [Linux](https://redis.io/docs/latest/operate/oss_and_stack/install/install-redis/install-redis-on-linux/)
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
* Username: superadmin@gmail.com Password: 12345678

## Docker

[Docker File](DOCKER.md)

## Passport API
```bash
php artisan passport:install
php artisan passport:client --personal
```

## Deployment
```bash
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

sudo chmod -R 775 storage
sudo chmod -R ugo+rw storage
```

## Schedule
```bash
crontab -e
Add the command below to the last line
* * * * * php /var/www/html/bitgrid/artisan schedule:run
```

## Icon

* [Solar Icons Set](https://www.figma.com/community/file/1166831539721848736)
* [Iconify](https://icon-sets.iconify.design)
* [Lucide](https://lucide.dev/icons/)

## New Feature Low-Code

* Page Builder (coming soon)
* Workflow Builder (coming soon)

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/bitgrid) project.

## Changelog

Please see [Releases](../../releases) for more information about what has changed recently.

## Security

Please review [our security policy](https://github.com/nakornsoft/bitgrid/security/policy) on how to report security vulnerabilities.

## Credits

* [Nakornsoft](https://www.nakornsoft.com)

## Support us

* [Paypal](https://www.paypal.me/nakornsoft)

## For Job

* [LinkedIn](https://www.linkedin.com/in/nakornsoft)
* [WhatsApp](https://web.whatsapp.com/send?phone=66989855565)
* [Line](https://line.me/ti/p/@677htpdk)

## License

Bitgrid is released under the [GNU GENERAL PUBLIC License](license.txt).
