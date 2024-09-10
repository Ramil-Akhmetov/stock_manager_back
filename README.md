# Складской учет

## Установка

Установите зависимости

```bash
composer install
```

Создайте .env из шаблона

```bash
cp .env.example .env
```

Введите необходимые данные для подключения к MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eljur
DB_USERNAME=root
DB_PASSWORD=root
```

В самом начале я игрался немного с SMTP, как минимум после регистрации пользователю на почту приходит сообщение если есть желание можно указать данные и для него

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Запустите миграций и сидеры

```bash
php artisan migrate --seed
```

Если БД нужно пересоздать и прогнать сидеры

```bash
php artisan migrate:fresh --seed
```

Сгенерируйте ключи шифрования

```bash
php artisan key:generate
php artisan passport:install
```

Запуск локального сервера через консоль

```bash
php artisan serve
```

### Учетная запись администратора

email: <admin@email.com>

пароль: password

### Учетная запись Ответственного лица

email: <responsible@email.com>

пароль: password

### Учетная запись Кладовщика

email: <keeper@email.com>

пароль: password

Для всех учетных записей по умолчанию пароль password

--------

После запуска сервера необходимо запустить фронт
<https://github.com/Ramil-Akhmetov/stock_manager_front>
