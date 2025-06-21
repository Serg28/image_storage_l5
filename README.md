# Linecore Image Storage

Advanced media storage package for Laravel 10-12, designed for working with images, videos and documents.

**Note**: This package is based on the original work by Artur (artur@vis-design.com). We've adapted and modernized it for current Laravel versions while maintaining full functionality.

## Sections
1. [Installation](#Installation)
2. [Configuration](#Configuration)
3. [Config Settings](#Config-Settings)
    1. [Конфиг изображений](#Конфиг-изображений)
    2. [Конфиг документов](#Конфиг-документов)
    3. [Конфиг видео](#Конфиг-видео)
        * [Конфиг видео API](#Конфиг-видео-api)
4. [Спецификация и примеры](#Спецификация-и-примеры)
    1. [Общая спецификация](#Общая-спецификация)
    3. [Использование изображений](#Использование-изображений)
    4. [Использование фотогалереи](#Использование-фотогалереи)
    5. [Использование документов](#Использование-документов)
    6. [Использование видео](#Использование-видео)
        * [Использование видео API](#Использование-видео-api)
    7. [Использование видеогалереи](#Использование-видеогалереи)
    8. [Использование тэгов](#Использование-тэгов)
5. [Кэширование](#Кэширование)
6. [Admin Interface](#admin-interface)

## Installation

Add the repository to your `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://git.linecore.com/linecore-laravel/packages/image_storage.git"
        }
    ]
}
```

Install via Composer:
```bash
composer require linecore/image-storage-laravel
```

For Laravel 10+ the service provider will be auto-discovered. For older versions, add to config/app.php:
```php
Linecore\ImageStorage\ImageStorageServiceProvider::class,
```

Run migrations:
```bash
php artisan migrate
```

Publish config, assets and views:
```bash
php artisan vendor:publish --provider="Linecore\ImageStorage\ImageStorageServiceProvider" --force
```

## Migration from vis/artur_image_storage_l5

If you're migrating from the original `vis/artur_image_storage_l5` package:

1. **Update your composer.json:**
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

2. **Update namespace imports in your code:**
   Replace all occurrences of:
   ```php
   use Vis\ImageStorage\...
   ```
   with:
   ```php
   use Linecore\ImageStorage\...
   ```

3. **Run migrations:**
   The package includes automatic data migration from old `vis_*` tables to new `linecore_*` tables:
   ```bash
   php artisan migrate
   ```

4. **Update published assets:**
   ```bash
   php artisan vendor:publish --provider="Linecore\ImageStorage\ImageStorageServiceProvider" --tag=public --force
   ```

5. **Update configuration references:**
   If you have any hardcoded references to `packages/vis/image-storage` in your views or code, update them to `packages/linecore/image-storage`.

6. **Update route names in your code:**
   If you're using the package's route names, update them:
   ```php
   // Old route names
   route("vis_images_show_single", [$slug])
   route("vis_galleries_show_single", [$slug])
   route("vis_videos_show_single", [$slug])
   route("vis_video_galleries_show_single", [$slug])
   
   // New route names
   route("linecore_images_show_single", [$slug])
   route("linecore_galleries_show_single", [$slug])
   route("linecore_videos_show_single", [$slug])
   route("linecore_video_galleries_show_single", [$slug])
   ```

7. **Update admin menu configuration:**
   If you have admin menu configuration, update the links:
   ```php
   // Old links
   '/image_storage/images'
   '/image_storage/galleries'
   
   // New links (same, no change needed)
   '/admin/image_storage/images'
   '/admin/image_storage/galleries'
   ```

### Breaking Changes from Original Package

- **Namespace:** `Vis\ImageStorage` → `Linecore\ImageStorage`
- **Table names:** `vis_*` → `linecore_*` (automatic migration included)
- **Asset path:** `packages/vis/image-storage` → `packages/linecore/image-storage`
- **Dependencies:** Replaced `vis/curl_client_l5` with `guzzlehttp/guzzle`
- **Image optimization:** Replaced deprecated `OptmizationImg` with `Intervention\Image`
- **Route names:** `vis_*` route names → `linecore_*` route names

## Admin Interface

The package provides admin routes for managing media content. All admin routes are prefixed with `/admin/image_storage/` and require authentication.

### Available Admin Routes:
- `/admin/image_storage/images` - Image management
- `/admin/image_storage/galleries` - Gallery management  
- `/admin/image_storage/videos` - Video management
- `/admin/image_storage/video_galleries` - Video gallery management
- `/admin/image_storage/documents` - Document management
- `/admin/image_storage/tags` - Tag management

### Admin Menu Integration Example:
If you're using an admin panel, you can add menu items like this:
```php
array(
    'title' => 'Media Storage',
    'icon'  => 'picture-o',
    'submenu' => array(
        array(
            'title' => "Images",
            'link'  => '/admin/image_storage/images',
        ),
        array(
            'title' => "Galleries", 
            'link'  => '/admin/image_storage/galleries',
        ),
        array(
            'title' => "Videos",
            'link'  => '/admin/image_storage/videos',
        ),
        array(
            'title' => "Video Galleries",
            'link'  => '/admin/image_storage/video_galleries',
        ),
        array(
            'title' => "Documents",
            'link'  => '/admin/image_storage/documents',
        ),
        array(
            'title' => "Tags",
            'link'  => '/admin/image_storage/tags',
        ),
    )
),
```


## Настройка конфигов
Все конфиги содержат 3 основных настройки:

Настройка title указывающее на имя раздела отображаемое в админ-панели
```php
    'title' => "Галереи",
```

Настройка per_page указывающее количество записей отображаемых на странице в админ-панели
```php
    'per_page' => 20,
```

Настройка fields содержащее набор полей, которые будут выводиться в форме редактирования записи. </br>
Значения: text, textarea, checkbox, select, datetime. </br>
Поддерживается динамическое создание новых полей и табов
```php
    'fields' => array(
        ...
    ),
```

### Конфиг изображений
Настройки валидации загружаемых изображений. В ошибки автоматически подставляются значения 'max_size' и 'extension_list'
```php
    'size_validation' => array(
        'enabled' => true,
        'max_size' => '1500000',
        'error_message' => "Превышен максимальный размер изображения в [size] MB"
    ),
    'extension_validation' => array(
        'enabled' => true,
        'allowed_extensions' => array('png', 'jpg', 'jpeg'),
        'error_message' => "Допустимы только изображения форматов: [extension_list]"
    ),
```

Настройка качества для загружаемых JPG изображений.
Значение: 0-100
```php
    'quality' => 85,
```

Настройка использования Intervention\Image для оптимизации загруженных изображений.</br>
Значение: true\false
```php
    'optimization' => true,
```

Настройка использования исходного названия изображения для поля title.</br>
Значение: true\false
```php
    'source_title' => true,
```

Настройка хранения метаданных изображения в базе данных.</br>
Применятся функция exif_read_data, данные хранятся в формате json.</br>
Значение: true\false
```php
    'store_exif' => true,
```

Настройка удаления файлов изображений при удалении сущности изображения.</br>
Значение: true\false

```php
    'delete_files' => true,
```
Настройка переименования файлов изображений при переименовании сущности изображения.</br>
Значение: true\false

```php
    'rename_files' => true,
```

Настройка отображения кнопки генерации новых размеров для записей.</br>
Применяется при необходимости сгененировать новый размер для уже существующих записей. </br>
Значение: true\false

```php
    'display_generate_new_size_button' => true,
```

Настройка генерируемых размеров изображений. </br>
Позволяет при загрузке изображений автоматически генерировать изображение в других размерах. </br>
Использует пакет для работы с изображениями [Intervention](http://image.intervention.io/). </br>
Модифицировать изображение можно в помощью настройки Modify принимающей параметры Intervention
```php
    'sizes' => array(
        'source' => array(
            'caption' => 'Оригинал',
            'default_tab' => true,
        ),
        'cms_preview' => array(
            'caption' => 'Превью в ЦМС',
            'default_tab' => false,
            'modify' => array(
                'fit' => array(160, 160, function (\Intervention\Image\Constraint $constraint) {
                    $constraint->upsize();
                }),
            ),
        ),
```

### Конфиг документов
Все параметры настройки аналогичны изображениям, кроме </br>
Настройка позволяющая хранить несколько файлов под одной сущности "документа". </br>
Может быть использовано когда необходимо выводить разные файлы на разных языках сайта</br>
При загрузке документа во все размеры устанавливается ссылка на исходный файл, которую потом можно заменить
```php
    'sizes' => array(
        'source' => array(
            'caption' => 'Основной файл',
            'default_tab' => true,
        ),
        'ua' => array(
            'caption' => 'Файл на укр',
            'default_tab' => false,
        ),
        'en' => array(
            'caption' => 'Файл на англ',
            'default_tab' => false,
        ),

    ),
```

### Конфиг видео
В массиве настройки fields два обязательных поля отвечающие за сервис и идентификатор видео
```php
    'api_provider' => array(
        'caption' => 'Видео сервис',
        'type' => 'select',
        'options' => config('image-storage.video_api.provider_names')
    ),
    'api_id' => array(
        'caption' => 'Идентификатор видео',
        'type' => 'text',
        'field' => 'string',
        'placeholder' => 'Идентификатор видео',
    ),
```

#### Конфиг видео API
Настройка управляющая отправление запрос к видео API, которые требуют ключи </br>
Значение: true\false
```php
    'enabled' => true,
```

Настройка времени кэширования ответа от видео API</br>
Значение: x, 0 (прим. 0 - вечность), false
```php
    'cache_minutes' => 60,
```

Настройка автоматического заполнения полей title&description из ответа видео API</br>
Значение: true\false
```php
    'set_data' => true,
```

Настройка имен провайдеров предоставляющих видео API. </br>
Выводится в select создании\редактировании видео
```php
    'provider_names' => array(
        'youtube' => 'Youtube',
        'vimeo'   => 'Vimeo',
    ),
```   

Настройка провайдеров предоставляющих видео API
```php
    'providers' => array(
        'youtube' => array(
         ...
        ),
        'vimeo' => array(
        ...
        ),
     )
```

Настройки каждого из видео API провайдеров</br>

Настройка проверки существования видео на сервисе (не требует ключа API)
```php
    'video_existence_url' => '',
```

Настройка ссылки на изображение-превью на сервисе (не требует ключа API)
```php
    'preview_url' => '',
```

Настройка качества изображения-превью на сервисе
```php
    'preview_quality' => '',
```

Настройка ссылки на просмотр видео (не требует ключа API)
```php
    'watch_url' => '',
```

Настройка ссылки на встраиваемое видео (не требует ключа API)
```php
    'embed_url' => '',
```

Настройка ссылки на сервис API
```php
    'api_url' => '',
```

Настройка данных которые будут запрошены у сервиса API
```php
    'api_part' => '',
```

Настройка ключа для подключения к сервису API
```php
    'api_key' => '',
```

## Usage Examples
### Basic Usage
To use the package models, import them in your class:
```php
use Linecore\ImageStorage\Gallery;
use Linecore\ImageStorage\Image;
use Linecore\ImageStorage\Tag;
use Linecore\ImageStorage\VideoGallery;
use Linecore\ImageStorage\Video;
use Linecore\ImageStorage\Document;
```    

All models extend Eloquent, so you can use standard Laravel query methods.
Models include built-in traits for translation and SEO functionality.

Для всех записей генерируется уникальных слаг, его значение можно получить с помощью метода
```php
    public function getSlug()
```

Общие scope фильтры для всех моделей (\Models\Traits\FilterableTrait.php)</br>
Фильтр сортировки по id.
```php
    public function scopeOrderId(Builder $query, $order = "desc")
```  

Фильтр выведения только активных записей.
```php
    public function scopeActive(Builder $query)
```

Фильтр выведения записей согласна массива активностей </br>
Значение: массив $activity[0,1]
```php
   public function scopeFilterByActivity(Builder $query, array $activity = [])
```   

Фильтр по slug записи
```php
    public function scopeSlug(Builder $query, $slug = '')
``` 

Фильтр по title записи
```php
    public function scopeFilterByTitle(Builder $query, $title = '')
```   

Фильтр по дате создания записи. </br>
Значение: массив $date['from' => '', to => '']
```php
    public function scopeFilterByDate(Builder $query, array $date = [])
```   

Фильтр по связанным тэгам. </br>
Значение: массив $tags[$idTags]
```php
    public function scopeFilterByTags(Builder $query, array $tags = [])
```

Eloquent связь с тэгами. Получает все связанные тэги с сущностями
```php
    public function tags()
```
  
### Использование изображений
Eloquent связь с галереями. Получает все связанные галереи с изображением
```php
    public function galleries()
```

Фильтр изображений по галереям.</br>
Значение: массив $galleries[$idGalleries]
```php
    public function scopeFilterByGalleries(Builder $query, array $galleries = [])
```

Метод получения ссылки на изображение по именному роуту. Роут необходимо определить самостоятельно
```php
    public function getUrl()
    {
        return route("linecore_images_show_single", [$this->getSlug()]);
    }
```

Наследует от абстракции src/Models/AbstractImageStorageFile.php следующие методы </br>
Получить путь изображения в указанном размере</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getSource($size = 'source')
```

Получить разрешение файла изображения</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileExtension($size = 'source')
``` 

Получить название файла изображения</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileName($size = 'source')
```  

Получить размер файла изображения</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileSize($size = 'source')
```  

Получить mime-type файла изображения</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileMimeType($size = 'source')
```  

### Использование фотогалереи
Eloquent связь с изображения. Получает все связанные изображения с галереей
```php
    public function images()
```

Фильтр галерей по наличию изображений
```php
    public function scopeHasImages(Builder $query)
```

Фильтр галерей по наличию активных изображений
```php
    public function scopeHasActiveImages(Builder $query)
```

Метод получения ссылки на галерею по именному роуту. Роут необходимо определить самостоятельно
```php
    public function getUrl()
    {
        return route("linecore_galleries_show_single", [$this->getSlug()]);
    }
```

Метод получения превью-изображения для галереи  </br>
Значение: один из указаных в конфиге изображений размеров, по умолчанию - cms_preview
```php
    public function getGalleryPreviewImage($size = 'cms_preview')
```

### Использование документов
Наследует от абстракции src/Models/AbstractImageStorageFile.php следующие методы </br>
Получить путь документа в указанном размере</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getSource($size = 'source')
```

Получить разрешение файла документа</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileExtension($size = 'source')
``` 

Получить название файла документа</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileName($size = 'source')
```  

Получить размер файла документа</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileSize($size = 'source')
```  

Получить mime-type файла документа</br>
Значение: один из указаных в конфиге размеров, по умолчанию - source
```php
    public function getFileMimeType($size = 'source')
```  

### Использование видео
Eloquent связь с изображением-превью. Получает объект установленного изображения-превью
```php
    public function preview()
```

Eloquent связь с изображения. Получает все связанные изображения с галереей
```php
    public function videoGalleries()
```

Связь с API провайдером. Получает объект API provider в зависимости от типа видео.
```php
    public function api()
```

Фильтр видео по видеогалереям галереям.</br>
Значение: массив $galleries[$idGalleries]
```php
    public function scopeFilterByVideoGalleries(Builder $query, array $galleries = [])
```

Метод получения id видео</br>
```php
    public function getSource()
```

Метод получения ссылки на видео по именному роуту. Роут необходимо определить самостоятельно
```php
    public function getUrl()
    {
        return route("linecore_videos_show_single", [$this->getSlug()]);
    }
```

Метод получения ссылки на изображение-превью видео. </br>
Получает или установленное изображение-превью или обращается API за изображением.
```php
    public function getPreviewImage($size = 'source')
```

#### Использование видео API
Видео API реализует интерфейс /Models/Interfaces/VideoAPIInterface.php и имеет следующие методы </br>

Метод получения ссылки на видео
```php
    public function getWatchUrl(array $urlParams);
```

Метод получения ссылки на встраиваемое видео 
```php
    public function getEmbedUrl(array $urlParams);
```

Метод получения ссылки на изображени-превью из API
```php
    public function getPreviewUrl();
```

Метод получения всех данных о видео из API
```php
    public function getApiResponse();
```

Метод получения названия видео из API
```php
    public function getTitle();
```

Метод получения описание видео из API
```php
    public function getDescription();
```

Метод получения количества просмотров видео из API
```php
    public function getViewCount();
```

Метод получения количества лайков видео из API
```php
    public function getLikeCount();
```

Метод получения количества дизлайков видео из API
```php
    public function getDislikeCount();
```

Метод получения количества favorite для видео  из API
```php
    public function getFavoriteCount();
```

Метод получения количества комментариев для видео  из API
```php
    public function getCommentCount();
```

### Использование видеогалереи
Eloquent связь с видео. Получает все связанные видео с видеогалереей
```php
    public function videos()
```

Фильтр видеогалерей по наличию изображений
```php
    public function scopeHasVideos(Builder $query)
```

Фильтр видеогалерей по наличию активных видео
```php
    public function scopeHasActiveVideos(Builder $query)
```

Метод получения ссылки на видеогалерею по именному роуту. Роут необходимо определить самостоятельно
```php
    public function getUrl()
    {
        return route("linecore_video_galleries_show_single", [$this->getSlug()]);
    }
```

### Использование тэгов
Eloquent связь с изображения. Получает все связанные изображения с тэгом
```php
    public function images()
```

Eloquent связь с документами. Получает все связанные изображения с тэгом
```php
    public function documents()
```

Eloquent связь с видео. Получает все связанные изображения с тэгом
```php
    public function videos()
```

Eloquent связь с галереями. Получает все связанные изображения с тэгом
```php
    public function galleries()
```

Eloquent связь с видеогалереями. Получает все связанные изображения с тэгом
```php
    public function videoGalleries()
```

## Кэширование
Медиахранилище использует ряд тегов для работы кэширования. </br>
При внесении измений в записи медиахранилища происходит автоматическое сбрасывание существующего кэша связанного с этими тегами.</br>
Перечень тэгов для каждой из сущностей:
* image-storage.video
* image-storage.document
* image-storage.video_gallery
* image-storage.gallery
* image-storage.image
* image-storage.tag

## Admin Interface Features

### General Management
The admin interface provides comprehensive management tools for all media types.
Each section includes:
- Filtering and search capabilities
- Bulk operations for multiple items
- Create/edit forms with configurable fields
- Pagination for large datasets

### Media Management Features
- **Image Management**: Upload, resize, optimize images with multiple size variants
- **Gallery Management**: Organize images into galleries with preview functionality  
- **Video Management**: Integrate with YouTube/Vimeo APIs for video metadata
- **Document Management**: Handle file uploads with multi-language support
- **Tag Management**: Categorize and filter media using tags

### Key Features
- Multiple file upload support
- Automatic image optimization using Intervention Image
- Configurable image sizes and quality settings
- Video API integration for automatic metadata retrieval
- Bulk operations for efficient media management
- SEO-friendly URLs and metadata management

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

Based on the original work by Artur (artur@vis-design.com). Adapted and modernized for Laravel 10-12 compatibility. 
