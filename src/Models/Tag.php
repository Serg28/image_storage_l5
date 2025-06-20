<?php namespace Linecore\ImageStorage;

class Tag extends AbstractImageStorage
{
    protected $table = 'linecore_tags';
    protected $configPrefix = 'tag';

    public function images()
    {
        return $this->morphedByMany('Linecore\ImageStorage\Image', 'entity', 'linecore_tags2entities', 'id_tag', 'id_entity');
    }

    public function documents()
    {
        return $this->morphedByMany('Linecore\ImageStorage\Document', 'entity', 'linecore_tags2entities', 'id_tag', 'id_entity');
    }

    public function videos()
    {
        return $this->morphedByMany('Linecore\ImageStorage\Video', 'entity', 'linecore_tags2entities', 'id_tag', 'id_entity');
    }

    public function galleries()
    {
        return $this->morphedByMany('Linecore\ImageStorage\Gallery', 'entity', 'linecore_tags2entities', 'id_tag', 'id_entity');
    }

    public function videoGalleries()
    {
        return $this->morphedByMany('Linecore\ImageStorage\VideoGallery', 'entity', 'linecore_tags2entities', 'id_tag', 'id_entity');
    }

    public function relateToTag($id, $type)
    {
        $this->$type()->syncWithoutDetaching($id);
        $this->flushCacheRelation($this->$type()->getRelated());
    }
}
