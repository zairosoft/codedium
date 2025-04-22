# [Bitgrid](https://github.com/nakornsoft/bitgrid)

> Laravel cms

ซอฟต์แวร์ CMS ออนไลน์ที่ออกแบบมาสำหรับธุรกิจขนาดเล็กและฟรีแลนซ์ Bitgrid ถูกสร้างขึ้นโดยนครซอฟต์ ใช้เทคโนโลยีสมัยใหม่ เช่น Laravel, Vite.js, Alpine.js, Tailwind, RESTful API เป็นต้น ด้วยโครงสร้างแบบโมดูลาร์ Bitgrid มี App Store ที่ยอดเยี่ยมให้กับผู้ใช้และนักพัฒนา

![Screen](https://www.nakornsoft.com/wp-content/uploads/2025/03/bitgrid.jpg "Dashboards")

[ภาษาไทย](README-TH.md) | [English](README.md)

## Requirements

* PHP 8.2 หรือ สูงกว่า
* ฐานข้อมูล (e.g.: MySQL, PostgreSQL, SQLite)
* Redis 5.0.14.1 หรือ สูงกว่า | [Windows](https://github.com/tporadowski/redis/releases/tag/v5.0.14.1) [Linux](https://redis.io/docs/latest/operate/oss_and_stack/install/install-redis/install-redis-on-linux/)
* Web Server (eg: Apache, Nginx, IIS)

## Framework

Bitgrid ใช้ [Laravel](http://laravel.com) ซึ่งเป็นเฟรมเวิร์ก PHP ที่ดีที่สุดที่มีอยู่เป็นเฟรมเวิร์กพื้นฐาน และแพ็กเกจ [Module](https://github.com/nWidart/laravel-modules) สำหรับแอป

## การติดตั้ง

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
```

* สร้างข้อมูลตัวอย่าง (optional): `php artisan  db:seed --class="SampleSeeder"`
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

## ใหม่ Feature Low-Code

* Page Builder (coming soon)
* Workflow Builder (coming soon)

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/bitgrid) project.

## Changelog

Please see [Releases](../../releases) for more information about what has changed recently.

## ความปลอดภัย

Please review [our security policy](https://github.com/nakornsoft/bitgrid/security/policy) on how to report security vulnerabilities.

## เครดิต

* [Nakornsoft](https://www.nakornsoft.com)
* [All Contributors](https://github.com/nakornsoft/bitgrid/graphs/contributors)

## สนับสนุนฉัน

* [Paypal](https://www.paypal.me/nakornsoft)

## จ้างเราทำงาน

* [LinkedIn](https://www.linkedin.com/in/nakornsoft)
* [WhatsApp](https://web.whatsapp.com/send?phone=66989855565)
* [Line](https://line.me/ti/p/@677htpdk)
* [Facebook](https://www.facebook.com/nakornsoft)

## ใบอนุญาต

Bitgrid is released under the [GNU GENERAL PUBLIC License](license.txt).

