#!/bin/bash

echo "🚀 Начинаем миграцию с vis/artur_image_storage_l5 на linecore/image-storage-laravel..."
echo ""

# Определяем ОС для правильного использования sed
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    SED_INPLACE="sed -i ''"
else
    # Linux
    SED_INPLACE="sed -i"
fi

echo "📝 1. Замена неймспейсов..."
find app/ config/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/use Vis\\ImageStorage/use Linecore\\ImageStorage/g' {} \; 2>/dev/null
find app/ config/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/Vis\\ImageStorage/Linecore\\ImageStorage/g' {} \; 2>/dev/null
echo "   ✅ Неймспейсы обновлены"

echo "🔗 2. Замена роутов..."
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_images_show_single/linecore_images_show_single/g' {} \; 2>/dev/null
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_galleries_show_single/linecore_galleries_show_single/g' {} \; 2>/dev/null
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_videos_show_single/linecore_videos_show_single/g' {} \; 2>/dev/null
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/vis_video_galleries_show_single/linecore_video_galleries_show_single/g' {} \; 2>/dev/null
echo "   ✅ Роуты обновлены"

echo "📁 3. Замена путей к ресурсам..."
find app/ resources/ -name "*.php" -type f -exec $SED_INPLACE 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \; 2>/dev/null
find resources/ -name "*.blade.php" -type f -exec $SED_INPLACE 's/packages\/vis\/image-storage/packages\/linecore\/image-storage/g' {} \; 2>/dev/null
echo "   ✅ Пути к ресурсам обновлены"

echo ""
echo "🎉 Автоматическая миграция завершена!"
echo ""
echo "📋 Следующие шаги:"
echo "   1. Обновите composer.json (добавьте репозиторий и зависимость)"
echo "   2. Выполните: composer update"
echo "   3. Запустите: php artisan migrate"
echo "   4. Обновите ресурсы: php artisan vendor:publish --provider=\"Linecore\\ImageStorage\\ImageStorageServiceProvider\" --tag=public --force"
echo ""
echo "🔍 Для проверки выполните:"
echo "   grep -r \"Vis\\\\ImageStorage\" app/ resources/ config/"
echo "   grep -r \"packages/vis/image-storage\" app/ resources/ config/"
echo "   grep -r \"vis_.*_show_single\" app/ resources/ config/"
echo ""
echo "Если команды выше ничего не найдут - миграция прошла успешно! ✨"