<?php

namespace Linecore\ImageStorage;

interface SluggableInterface
{
    public function makeUniqueSlug();

    public function getSlug();

    public function setSlug();
}