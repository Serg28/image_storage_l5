<?php

namespace Linecore\ImageStorage;

trait ErrorableTrait
{
    protected $errorMessage;

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }
}