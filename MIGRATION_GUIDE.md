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
    "repositories": [
        {
            "type": "vcs",
            "url": "https://git.linecore.com/linecore-laravel/packages/image_storage.git"
        }
    ],
    "require": {
        "linecore/image-storage-laravel": "^2.0"
    }
}
```

### 2. Обновление неймспейсов в коде

#### Автоматическая замена неймспейсов

Используйте следующие команды для автоматической замены во всем проекте:

```bash
# Замена в PHP файлах (use statements)
find app/ config/ resources/ -name "*.php" -type f -exec sed -i 's/use Vis\\ImageStorage/use Linecore\\ImageStorage/g' {} \;

# Замена прямых ссылок на классы
find app/ config/ resources/ -name "*.php" -type f -exec sed -i 's/Vis\\ImageStorage/Linecore\\ImageStorage/g' {} \;

# Для macOS используйте:
find app/ config/ resources/ -name "*.php" -type f -exec sed -i '' 's/use Vis\\ImageStorage/use Linecore\\ImageStorage/g' {} \;
find app/ config/ resources/ -name "*.php" -type f -exec sed -i '' 's/Vis\\ImageStorage/Linecore\\ImageStorage/g' {} \;
```

#### Ручная проверка

После автоматической замены проверьте следующие места:
- `use` statements в PHP файлах
- Ссылки на классы в конфигурации
- Любые другие упоминания в коде

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

#### Автоматическая замена роутов

```bash
# Замена именованных роутов
find app/ resources/ -name "*.php" -type f -exec sed -i 's/vis_images_show_single/linecore_images_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec sed -i 's/vis_galleries_show_single/linecore_galleries_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec sed -i 's/vis_videos_show_single/linecore_videos_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec sed -i 's/vis_video_galleries_show_single/linecore_video_galleries_show_single/g' {} \;

# Для macOS используйте:
find app/ resources/ -name "*.php" -type f -exec sed -i '' 's/vis_images_show_single/linecore_images_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec sed -i '' 's/vis_galleries_show_single/linecore_galleries_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec sed -i '' 's/vis_videos_show_single/linecore_videos_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec sed -i '' 's/vis_video_galleries_show_single/linecore_video_galleries_show_single/g' {} \;
```

#### Примеры изменений:

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

### 5. Обновление путей к ресурсам

#### Автоматическая замена путей

```bash
# Замена путей к ресурсам
find app/ resources/ -name "*.php" -type f -exec sed -i 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \;
find resources/ -name "*.blade.php" -type f -exec sed -i 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \;

# Для macOS используйте:
find app/ resources/ -name "*.php" -type f -exec sed -i '' 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \;
find resources/ -name "*.blade.php" -type f -exec sed -i '' 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \;
```

#### Примеры изменений:

```
// Старые пути
packages/vis/image-storage

// Новые пути  
packages/linecore/image-storage
```

### 6. Полная автоматическая миграция

Создайте файл `migrate_to_linecore.sh` и выполните все замены одной командой:

```bash
#!/bin/bash

echo "Начинаем миграцию с vis/artur_image_storage_l5 на linecore/image-storage-laravel..."

# Определяем ОС для правильного использования sed
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    SED_INPLACE="sed -i ''"
else
    # Linux
    SED_INPLACE="sed -i"
fi

echo "1. Замена неймспейсов..."
find app/ config/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/use Vis\\ImageStorage/use Linecore\\ImageStorage/g' {} \;
find app/ config/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/Vis\\ImageStorage/Linecore\\ImageStorage/g' {} \;

echo "2. Замена роутов..."
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_images_show_single/linecore_images_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_galleries_show_single/linecore_galleries_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_videos_show_single/linecore_videos_show_single/g' {} \;
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_video_galleries_show_single/linecore_video_galleries_show_single/g' {} \;

echo "3. Замена путей к ресурсам..."
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \;
find resources/ -name "*.blade.php" -type f -exec $SED_INPLACE 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \;

echo "Миграция завершена! Не забудьте:"
echo "- Обновить composer.json"
echo "- Выполнить composer update"
echo "- Запустить php artisan migrate"
echo "- Обновить опубликованные ресурсы"
```

Сделайте скрипт исполняемым и запустите:
```bash
chmod +x migrate_to_linecore.sh
./migrate_to_linecore.sh
```

### 7. Проверка после миграции

Для проверки что все заменилось правильно:

```bash
# Найти оставшиеся упоминания старого пакета
grep -r "Vis\\ImageStorage" app/ resources/ config/
grep -r "packages/vis/image-storage" app/ resources/ config/
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