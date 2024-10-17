# Short Url App

## Стек технологий

- PHP 8.2
- Yii2 Framework
- MySql
- Redis
- Supervisor
- Nginx

## Запуск проекта в Docker

### 1. Установка Docker и Docker Compose

Убедитесь, что у вас установлены [Docker](https://www.docker.com/get-started) и [Docker Compose](https://docs.docker.com/compose/install/).

### 2. Клонирование репозитория

Клонируйте репозиторий с проектом:

```bash
git clone https://github.com/hapcoun/shorturl-app.git
cd cryptocalc
```

### 3. Запустите контейнеры с помощью команды:

```bash
docker-compose up --build 
```

## Реализация

### 1. Написаны функция и триггер для генерации короткого урла длиной от 1 до 5 символов, тем самым решая проблемы, если количество запросов в секунду на генерацию будет высокое

### 2. При переходи по ссылке, сохраняем количество переходов в NoSql Redis таблицу.

### 3. Фоновая команда просматривает Redis-записи и сохраняет их в базе данных.

