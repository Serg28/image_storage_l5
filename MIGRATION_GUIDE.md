# Руководство по миграции с vis/artur_image_storage_l5

## Для вашего существующего сайта

### 1. Обновление composer.json

Замените в вашем `composer.json`:
```json
{
    "require": {
        "vis/artur_image_storage_l5": "2.*"
    }
}
```

На:
```json
{
    "require": {
        "linecore/image-storage-laravel": "^2.0"
    }
}
```

### 2. Обновление неймспейсов в коде

Вам нужно будет найти и заменить все использования старого неймспейса на новый:

**Найти:** `Vis\ImageStorage`  
**Заменить на:** `Linecore\ImageStorage`

Это касается:
- `use` statements в PHP файлах
- Ссылок на классы в конфигурации
- Любых других упоминаний в коде

### 3. Команды для выполнения

```bash
# Обновить зависимости
composer update

# Запустить миграции (автоматически перенесет данные из vis_* таблиц в linecore_*)
php artisan migrate

# Обновить опубликованные ресурсы
php artisan vendor:publish --provider="Linecore\ImageStorage\ImageStorageServiceProvider" --tag=public --force
```

### 4. Обновление роутов

Если в вашем коде используются именованные роуты пакета, обновите их:

```php
// Старые роуты
route("vis_images_show_single", [$slug])
route("vis_galleries_show_single", [$slug])
route("vis_videos_show_single", [$slug])
route("vis_video_galleries_show_single", [$slug])

// Новые роуты
route("linecore_images_show_single", [$slug])
route("linecore_galleries_show_single", [$slug])
route("linecore_videos_show_single", [$slug])
route("linecore_video_galleries_show_single", [$slug])
```

### 5. Проверка путей к ресурсам

Если в ваших view файлах есть прямые ссылки на:
```
packages/vis/image-storage
```

Замените их на:
```
packages/linecore/image-storage
```

### 6. Автоматический поиск и замена

Для автоматизации процесса можете использовать:

```bash
# Найти все файлы с использованием старого неймспейса
grep -r "Vis\\ImageStorage" app/ resources/ config/

# Найти все файлы с путями к старым ресурсам
grep -r "packages/vis/image-storage" app/ resources/ config/

# Найти все файлы с использованием старых роутов
grep -r "vis_.*_show_single" app/ resources/ config/
```

## Что изменилось

### Неймспейсы
- `Vis\ImageStorage\*` → `Linecore\ImageStorage\*`

### Таблицы базы данных
- `vis_images` → `linecore_images`
- `vis_galleries` → `linecore_galleries`
- `vis_videos` → `linecore_videos`
- `vis_tags` → `linecore_tags`
- И все остальные таблицы с префиксом `vis_`

### Пути к ресурсам
- `packages/vis/image-storage` → `packages/linecore/image-storage`

### Именованные роуты
- `vis_images_show_single` → `linecore_images_show_single`
- `vis_galleries_show_single` → `linecore_galleries_show_single`
- `vis_videos_show_single` → `linecore_videos_show_single`
- `vis_video_galleries_show_single` → `linecore_video_galleries_show_single`

### Зависимости
- Обновлена поддержка Laravel 10-12
- Заменен `vis/curl_client_l5` на `guzzlehttp/guzzle`
- Заменен `vis/optimization_img` на `intervention/image`

## Обратная совместимость

Пакет включает автоматическую миграцию данных, поэтому ваши существующие данные будут сохранены и перенесены в новые таблицы.

## Поддержка

Если возникнут проблемы при миграции, проверьте:
1. Все ли неймспейсы обновлены
2. Запущены ли миграции
3. Обновлены ли пути к ресурсам