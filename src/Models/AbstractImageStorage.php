<?php namespace Linecore\ImageStorage;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractImageStorage extends Model implements
    CacheableInterface,
    ChangeableSchemeInterface,
    ConfigurableInterface,
    ErrorableInterface,
    FilterableInterface,
    RelatableInterface,
    SluggableInterface,
    URLableInterface
{
    use CacheableTrait,
        ChangeableSchemeTrait,
        ConfigurableTrait,
        ErrorableTrait,
        FilterableTrait,
        RelatableTrait,
        SluggableTrait,
        URLableTrait;

    protected $table;
    protected $fillable = ['id'];

    public function setFields($fields)
    {
        $this->doCheckSchemeFields();

        $columnNames = $this->getConfigFieldsNames();

        foreach ($columnNames as $key => $columnName) {
            $value = isset($fields[$columnName]) ? $fields[$columnName] : false;
            $this->$columnName = $value;
        }
    }
}
