<p align="center">
  <a href="https://tempestphp.com">
    <img src="https://raw.githubusercontent.com/tempestphp/.github/main/logo_current/tempest-logo.png" width="100" />
  </a>
</p>

<h1 align="center">Tempest scaffold</h1>
<div align="center">
  A simple CRUD REST API using the Tempest framework.
</div>

## Persyaratan

- PHP 8.5+
- Composer
- MariaDB (atau MySQL)

## Instalasi

1. **Clone repositori:**

    ```bash
    git clone <url-repositori-anda>
    cd <nama-direktori>
    ```

2. **Instal dependensi:**

    ```bash
    composer install
    ```

3. **Buat file lingkungan:**
    Salin `.env.example` menjadi `.env` dan isi dengan kredensial basis data Anda.

    ```bash
    cp .env.example .env
    ```

    Pastikan untuk mengisi `DB_USER`, `DB_PASSWORD`, dan `DB_NAME`.

4. **Jalankan server pengembangan:**
    Aplikasi akan berjalan di `http://localhost:3003` sesuai konfigurasi di `.env`.

    ```bash
    composer serve
    ```

## Menjalankan Pengujian

Untuk menjalankan pengujian unit, gunakan perintah berikut:

```bash
composer test
```
