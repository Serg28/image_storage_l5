<?php namespace Linecore\ImageStorage;

use Illuminate\Database\Eloquent\Builder;

class Gallery extends AbstractImageStorageGallery
{
    protected $table = 'linecore_galleries';
    protected $configPrefix = 'gallery';

    protected $galleryRelation = 'images';

    public function images()
    {
        return $this
            ->belongsToMany('Linecore\ImageStorage\Image', 'linecore_images2galleries', 'id_gallery', 'id_image')
            ->orderBy('priority', 'desc')
            ->withPivot('is_preview');
    }

    public function scopeHasImages(Builder $query)
    {
        return parent::scopeHasRelated($query);
    }

    public function scopeHasActiveImages(Builder $query)
    {
        return parent::scopeHasRelatedActive($query);
    }

}
