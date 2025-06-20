<?php

namespace Linecore\ImageStorage;

interface ErrorableInterface
{
    public function getErrorMessage();

    public function setErrorMessage(string $errorMessage);
}