# ShareGifts

Demo app за споделяне на подаръци с **категории**, **снимки**, **коментари** и **гласуване**.

**Tech stack:** PHP 8.2+, Laravel 12, Livewire v3, Bootstrap 5, Vite

---

## Изтегляне от GitHub

```bash
git clone https://github.com/vlkf/sharegifts.git
cd sharegifts
```

---

## Изисквания

* PHP **^8.2**
* Composer
* Node.js + npm
* DB: **MySQL/MariaDB**

---

## Инсталация

### 1) Инсталация на PHP зависимости

```bash
composer install
```

### 2) Environment файл + app key generation

```bash
cp .env.example .env
php artisan key:generate
```

---

## База данни

В `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sharegifts
DB_USERNAME=root
DB_PASSWORD=
```

---

## Миграции + демо данни (seed)


```bash
php artisan migrate:fresh --seed
```

---

## Storage линк (за снимки)

```bash
php artisan storage:link
```

---

## Frontend (Vite)

```bash
npm install
npm run dev
```

Production build:

```bash
npm run build
```

---

## Стартиране

В отделен терминал:

```bash
php artisan serve
```

Отвори:

* [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

##  Демо логин

* Email: `test@test.com`
* Password: `password`
