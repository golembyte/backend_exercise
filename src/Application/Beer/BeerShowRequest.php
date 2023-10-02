<?php

namespace App\Application\Beer;

use App\Application\Shared\BaseRequest;
use Assert\Assertion;

class BeerShowRequest extends BaseRequest
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @param mixed $id
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * @param mixed $id
     * @throws \Assert\AssertionFailedException
     */
    private function setId($id)
    {
        Assertion::notEmpty($id, 'No id provided.', 'id');
        $this->id = $id;
    }
}
