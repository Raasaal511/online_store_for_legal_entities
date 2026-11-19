# СнабПлюс — страница «Для юридических лиц»

Адаптивный B2B-лендинг с формой заявки, хранением обращений в MySQL и админ-кабинетом для проверки заявок.

Стек: HTML, CSS, JavaScript, PHP 8.2+, MySQL.

## Что реализовано

- Заголовок и описание сотрудничества
- Блок преимуществ
- Информация об оптовых ценах и индивидуальных скидках
- Форма заявки: компания, имя, телефон, email
- Админ-кабинет: список заявок, статусы, фильтры
- Демо-данные для наглядной проверки
- Автотесты PHPUnit

## Быстрый старт

### 1. Зависимости

```bash
composer install
cp .env.example .env
```

### 2. База данных

Поднимите MySQL и примените схему:

```bash
# пример: Docker
docker run -d --name snabplus-mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=snabplus \
  -e MYSQL_USER=snabplus \
  -e MYSQL_PASSWORD=snabplus \
  -p 3307:3306 mysql:8.0

docker exec -i snabplus-mysql mysql -uroot -proot --default-character-set=utf8mb4 < sql/schema.sql
docker exec -i snabplus-mysql mysql -uroot -proot --default-character-set=utf8mb4 < sql/seed.sql
```

Либо:

```bash
php bin/setup-db.php
```

### 3. Запуск

```bash
composer serve
```

Откройте:

- Публичная страница: http://localhost:8080/
- Админ-кабинет: http://localhost:8080/admin/login

## Доступ в админку (демо)

- Логин: `admin`
- Пароль: `Admin123!`

Пароль задаётся в `.env` (`ADMIN_LOGIN`, `ADMIN_PASSWORD`).

## Как устроена заявка

1. Пользователь отправляет форму `POST /request`.
2. Сервер проверяет CSRF, honeypot и валидирует поля.
3. `ApplicationService` сохраняет запись через `PdoApplicationRepository`.
4. Менеджер видит заявку в `/admin`, фильтрует и меняет статус:
   - `new` — новая
   - `in_progress` — в работе
   - `done` — закрыта
   - `rejected` — отклонена

## Структура проекта

```text
public/          # document root
src/             # домен, сервисы, репозитории, HTTP
templates/       # представления лендинга и админки
sql/             # схема и демо-данные
tests/           # PHPUnit
```

Код разделён по слоям: контроллеры тонкие, бизнес-логика в сервисах, доступ к данным за интерфейсом репозитория. Так проще сопровождать проект и переносить его в CMS.

## Совместимость с 1С-Битрикс

Страница сейчас автономна, но перенос в Битрикс рассчитан без переписывания бизнес-логики.

1. Создайте локальный компонент `local/components/snabplus/b2b.lead/`.
   В `class.php` оставьте вызов текущего `ApplicationService`, в `templates/.default/template.php` перенесите разметку из `templates/landing`.
2. Заведите HL-блок «B2B-заявки» с полями:
   `UF_COMPANY`, `UF_CONTACT_NAME`, `UF_PHONE`, `UF_EMAIL`, `UF_STATUS`, `UF_CREATED_AT`.
   Это те же поля, что в таблице `applications`.
3. Напишите реализацию `ApplicationRepositoryInterface` на HighloadBlock API (или инфоблок) и подставьте её вместо `PdoApplicationRepository`.
   Интерфейс сервиса менять не нужно.
4. Создайте раздел сайта `/dlya-yuridicheskih-lits/` и подключите компонент на странице раздела.
   Стили подключите через `Bitrix\Main\Page\Asset` или шаблон сайта.
5. Для менеджеров используйте стандартный UI HL-блока в админке Битрикс
   либо оставьте текущий кабинет как кастомную страницу, читающую те же сущности.

Такой путь сохраняет валидацию, статусы и UX формы, меняя только способ хранения и точку встраивания в сайт.

## Тесты

```bash
composer test
```

Покрыто:

- валидация полей заявки
- создание заявки
- фильтры и смена статуса
- CSRF и авторизация админки
