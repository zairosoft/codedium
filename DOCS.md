# Module
# https://nwidart.com/laravel-modules/v6/advanced-tools/artisan-commands

php artisan module:make Invoices

php artisan module:make-model invoice Invoices

php artisan module:make-controller ApiInvoicesController Invoices

php artisan module:make-migration create_invoices_table Invoices

php artisan module:migrate Inventory

php artisan module:migrate-refresh Inventory

php artisan module:delete Invoices

php artisan module:enable Invoices

php artisan module:update Invoices

php artisan module:seed Inventory --class="InventoryDatabaseSeeder"



php artisan make:migration create_users_table

php artisan make:model InvoiceModel

php artisan make:controller InvoicesController

php artisan migrate:refresh

php artisan migrate:refresh --path=/database/migrations/2023_01_01_000000_create_users_table.php

composer self-update --1


# NodeJS
npm install
npm run dev

npm run build
