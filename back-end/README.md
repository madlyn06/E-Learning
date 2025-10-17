# Newnet Framework

## Setup project

### 1. Cài đặt composer

```
composer install
```

### 2. Tạo file .env

```
cp .env.example .env
```

### 3. Generate key

```
php artisan key:generate
```

### 4. Link storage

```
php artisan storage:link
```

### 5. Link theme asset

```
php artisan cms:theme.link
php artisan cms:link-admin-ui
```

Publish assets
```
php artisan vendor:publish
```
Tìm *Tag* **module-assets** và chọn nó (~~enter~~).

### 6. Setup database

```
php artisan migrate
```

### 7. Tạo tài khoản admin

```
php artisan cms:create-admin
```

### 8. Khởi chạy

```
php artisan serve
```

Login vào admin: [http:127.0.0.1:8000/admin](http:127.0.0.1:8000/admin) 

---

### Lưu ý khác:
