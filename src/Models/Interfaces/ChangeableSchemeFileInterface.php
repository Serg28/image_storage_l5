<?php namespace Linecore\ImageStorage;

interface ChangeableSchemeFileInterface
{
    public function doCheckSchemeFields();

    public function doUpdateSizes();

}
