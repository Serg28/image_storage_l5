<?php namespace Linecore\ImageStorage;

use Illuminate\Database\Eloquent\Builder;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use Exception;


class Image extends AbstractImageStorageFile
{
    protected $table = 'linecore_images';
    protected $configPrefix = 'image';
    protected $relatableList = ['galleries', 'tags'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Image $item) {
            $item->setImageExifData();
        });
    }

    public function galleries()
    {
        return $this->belongsToMany('Linecore\ImageStorage\Gallery', 'linecore_images2galleries', 'id_image', 'id_gallery');
    }

    public function scopeFilterByGalleries(Builder $query, array $galleries = [])
    {
        if (!$galleries) {
            return $query;
        }

        $relatedImagesIds = self::whereHas('galleries', function (Builder $query) use ($galleries) {
            $query->whereIn('id_gallery', $galleries);
        })->pluck('id');

        return $query->whereIn('id', $relatedImagesIds);
    }

    public function makeFile($size = 'source')
    {
        $extension = $this->makeExtension($size);

        if ($extension == 'svg') {
            return parent::makeFile($size);
        }

        $sourceFile = $this->sourceFile ? $this->sourceFile->getRealPath() : $this->getPublicPath();

        $img = InterventionImage::make($sourceFile);

        $info = $this->getConfigSizeInfo($size);

        if (isset($info['modify'])) {
            foreach ($info['modify'] as $method => $args) {
                call_user_func_array([$img, $method], $args);
                if ($method == 'resizeCanvas' && (isset($args[4]) && preg_match('~rgba\(\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*[01]+\.?[0-9]?\)~', $args[4]))) {
                    $extension = 'png';
                }
            }
        }

        $destinationPath = public_path() . $this->makeFolders($size);
        $fileName = $this->makeFileName() . "." . $extension;

        $img->save($destinationPath . $fileName, $this->getConfigQuality());

        $this->{$this->sizePrefix . $size} = $size . "/" . $fileName;
        $this->sourceFile = null;

        return true;
    }

    public function optimizeImage($size)
    {
        if (!$this->getConfigOptimization()) {
            return false;
        }

        $sizes = $size ? [$size => ''] : $this->getConfigSizes();
        foreach ($sizes as $size => $info) {
            $this->optimizeImage($this->getSource($size));
        }

        return true;
    }

    private function setImageExifData()
    {
        if (!$this->getConfigStoreEXIF()) {
            return false;
        }

        if (!$this->sourceFile) {
            return false;
        }

        try {
            $imageData = exif_read_data($this->sourceFile, 0, true);
            $this->exif_data = json_encode($imageData);
            $this->date_time_source = isset($imageData['EXIF']['DateTimesource']) ? $imageData['EXIF']['DateTimesource'] : "2035-01-01 00:00:00";

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Optimize image file
     * 
     * @param string $imagePath
     * @return bool
     */
    private function optimizeImage($imagePath)
    {
        if (!$this->getConfigOptimization()) {
            return false;
        }

        try {
            $fullPath = public_path($imagePath);
            if (!file_exists($fullPath)) {
                return false;
            }

            // Basic optimization using Intervention Image
            $image = InterventionImage::make($fullPath);
            $image->save($fullPath, $this->getConfigQuality());
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
