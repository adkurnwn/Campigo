<div align="center">

# <span style="color: #0d9488; font-family: 'Figtree', sans-serif;">Campigo</span>

</div>

> Website Campigo, penyewaan alat camping: mata kuliah pemograman backend

## Install and .env setup

1. composer install
2. npm install
3. cp .env.example .env


## Database setup

1. Buat database pada mysql sesuai dengan env
2. Modified file .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campigo
DB_USERNAME=root
DB_PASSWORD=
```
3. Isi email dan password app email pada .env

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@email.com
MAIL_PASSWORD= isi_dengan_password_app_dari_gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="email@email.com"
MAIL_FROM_NAME="${APP_NAME}"
```

4. php artisan key:generate
5. php artisan migrate --seed
6. php artisan storage:link

## Usage

Gunakan Laragon / XAMPP untuk memulai database server dan webserver, atau gunakan ini sebagai pengganti webserver:
```sh
php artisan serve
```
lalu:
```sh
npm run dev
```


## Use Case dan activity diagram

<a href="https://drive.google.com/file/d/1qZ7QS_9L9HYgD3U2nVNoyp-SbHB-jdE2/view?usp=drive_link" target="_blank">Draw.io</a>

## Design Web

<a href="https://drive.google.com/file/d/1qZ7QS_9L9HYgD3U2nVNoyp-SbHB-jdE2/view?usp=drive_link">Figma</a>
