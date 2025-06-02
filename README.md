# [Codedium](https://github.com/nakornsoft/codedium)

> Laravel cms (Developing)

Online cms software designed for small businesses and freelancers. Bitgrid is built with modern technologies such as Laravel, Vite.js, Alpine.js, Tailwind, RESTful API etc. Thanks to its modular structure, Codedium provides an awesome App Store for users and developers.

![Screen](https://www.nakornsoft.com/assets/2025/04/bitgrid.webp "Dashboards")

[English](README.md) | [ภาษาไทย](README-TH.md)

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
* For linux install create folder link modules
```bash
ln -s modules Modules
```
or

> Change config modules - > vendor/nwidart/laravel-modules/config/config.php
> 
> 'modules' => base_path('Modules') to 'modules' => base_path('modules')
* Install Bitgrid:

```bash
php artisan migrate
php artisan module:publish
php artisan storage:link
```

* Create sample data (optional): `php artisan  db:seed --class="SampleSeeder"`
* Username: super@gmail.com Password: super

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
sudo chmod -R ugo+rw bootstrap/cache/
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
* [All Contributors](https://github.com/nakornsoft/bitgrid/graphs/contributors)

## Support Me

* [Paypal](https://www.paypal.me/nakornsoft)

## Hire us for work

* [LinkedIn](https://www.linkedin.com/in/nakornsoft)
* [WhatsApp](https://web.whatsapp.com/send?phone=66989855565)
* [Line](https://line.me/ti/p/@677htpdk)
* [Facebook](https://www.facebook.com/nakornsoft)

## License

Bitgrid is released under the [GNU GENERAL PUBLIC License](license.txt).
