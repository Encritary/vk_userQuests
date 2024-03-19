# vk_userQuests

Тестовое задание на стажировку по вакансии [«Программист-разработчик«](https://internship.vk.company/vacancy/810) в команду разработки мини-приложений.

## Требования

Минимальные требования для запуска проекта:
- PHP 8.1 CLI
- Composer
- MySQL

## Установка

Перед запуском нужно установить проект через Composer:

```shell
$ composer install
```

## База данных

Для сервиса может использоваться любая база данных, поддерживаемая расширением PDO для PHP.

В папке `sql` можно найти [схему базы данных](https://github.com/Encritary/vk_userQuests/blob/main/sql/mysql_schema.sql) для MySQL.

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

## Сборка через Docker

Чтобы собрать образ Docker для сервиса, можно воспользоваться командой:

```shell
$ docker build -t user_quests .
```

Затем можно запустить образ Docker, указав в качестве тома конфигурационный файл и пробросив порт:

```shell
$ docker run -v ./config.json:/app/config.json -p 8080:8080/tcp user_quests
```

## Документация

Документацию по API можно прочитать [в отдельном файле](https://github.com/Encritary/vk_userQuests/blob/main/API.md).