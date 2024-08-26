<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About This Project

Template admin panel untuk starter sebuah project,

Beberapa fitur yang yang ada:

-   Halaman login
-   CRUD Submenu, Menu, User, Role, Permission
-   Dynamic sidebar menu dengan permission.

## Membuat menu/submenu

Dalam membuat menu kita dapat menentukan apakah menu memiliki child atau tidak. Kemudian agar display menu ditampilkan sesuai dengan role, buat nama permission `viewAny menu_name-submenu_name`

## Membuat proteksi controller

Buat proteksi dengan menyesuaikan nama permissino dengan method dari controller.

> if (!(request()->user()->can('viewAny~Menu-Menu')))
>
> return abort(403, 'unauthorized access');

### Demo Video

Link Demo: https://youtu.be/R9AW87ryWxY

## Tech Stack

Beberapa resource yang digunakan dalam projek ini:

-   Laravel 11
-   Bootstrap V5.3
-   jQuery
-   XAMPP (PHP 8.2 dan MySQL)
-   Zuramai Template Admin Panel

## Running This Project

-   Clone repository ini.
-   install dependency PHP `composer install`.
-   Buat app key `php artisan key:generate`.
-   Konfigurasikan environment (database dll) pada file .env
-   Buat database sesuai nama database pada file .env
-   Jalankan `php artisan migrate --seed` untuk membuat struktur database sekaligus mengisi data pada tabel.
