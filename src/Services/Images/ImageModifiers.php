<?php

namespace Vis\ImageStorage;

use Intervention\Image\Constraint;

class ImageModifiers
{
    public static function upsizeConstraint(Constraint $constraint)
    {
        $constraint->upsize();
    }
}

