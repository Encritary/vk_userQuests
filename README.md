# vk_userQuests

Тестовое задание на стажировку по вакансии [«Программист-разработчик«](https://internship.vk.company/vacancy/810) в команду разработки мини-приложений.

## Требования

Минимальные требования для запуска проекта:
- PHP 8.1 CLI
- Composer

## Установка

Перед запуском нужно установить проект через Composer:

```shell
$ composer install
```

## База данных

Для сервиса может использоваться любая база данных, поддерживаемая расширением PDO для PHP.

Сервис тестировался на СУБД MySQL. Также проверена работа с SQLite, так как он используется в юнит-тестах.

В папке `sql` можно найти схемы базы данных:
- [для MySQL](https://github.com/Encritary/vk_userQuests/blob/main/sql/mysql_schema.sql)
- [для SQLite](https://github.com/Encritary/vk_userQuests/blob/main/sql/sqlite_schema.sql)

## Настройка

Также перед запуском необходимо создать файл `config.json` и заполнить его согласно примеру конфигурации: `config.example.json`.

В конфигурации нужно указать данные для подключения к MySQL:

```json
{
  "db": {
    "dsn": "mysql:host=127.0.0.1;dbname=user_quests",
    "username": "someuser",
    "password": "123456"
  }
}
```

## Запуск

Чтобы запустить проект, следует воспользоваться ``PHP Built-in Web Server`` в папке проекта:

```shell
$ php -S localhost:8080 router.php
```

Указанная выше команда запустит проект при помощи роутер-файла ``router.php``.

## Тестирование

Можно проверить корректность логики кода, используя тесты PHPUnit.

Для этого можно воспользоваться командой:

```shell
$ composer test
```

## Сборка через Docker

Чтобы собрать образ Docker для сервиса, можно воспользоваться командой:

```shell
$ docker build -t user_quests .
```

Стоит заметить, что Dockerfile изначально собирается только с поддержкой SQLite и MySQL.
Если необходима поддержка иных СУБД, то следует дописать нужные расширения в строчку с командой `docker-php-ext-install`. 

Затем можно запустить образ Docker, указав в качестве тома конфигурационный файл и пробросив порт:

```shell
$ docker run -v ./config.json:/app/config.json -p 8080:8080/tcp user_quests
```

## Документация

Документацию по API можно прочитать [в отдельном файле](https://github.com/Encritary/vk_userQuests/blob/main/API.md).