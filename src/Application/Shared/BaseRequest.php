<?php

namespace App\Application\Shared;

abstract class BaseRequest
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->{$name} ?? null;
    }
}