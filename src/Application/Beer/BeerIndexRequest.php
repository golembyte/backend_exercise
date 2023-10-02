<?php

namespace App\Application\Beer;

use App\Application\Shared\BaseRequest;
use Assert\Assertion;
use Assert\AssertionFailedException;

class BeerIndexRequest extends BaseRequest
{
    const SEARCH_MIN_LIMIT = 1;
    const SEARCH_MIN_PAGE = 1;

    /**
     * @var mixed
     */
    protected $food;

    /**
     * @var mixed
     */
    protected $page;

    /**
     * @var mixed
     */
    protected $limit;

    /**
     * @param mixed $food
     * @param mixed $page
     * @param mixed $limit
     * @throws AssertionFailedException
     */
    public function __construct($food, $page, $limit)
    {
        $this->setFood($food);
        $this->setPage($page);
        $this->setLimit($limit);
    }

    /**
     * @param mixed $food
     * @throws AssertionFailedException
     */
    private function setFood($food)
    {
        Assertion::notEmpty($food, 'The "food" field is required', 'Search food');
        $this->food = $food;
    }

    /**
     * @param mixed $page
     */
    private function setPage($page)
    {
        Assertion::nullOrNumeric($page, 'The "page" field must be a number', 'Search page');
        Assertion::nullOrMin($page, self::SEARCH_MIN_PAGE, 'The "page" field must be a number greater than 1', 'Search page');

        $this->page = $page;
    }

    /**
     * @param mixed $limit
     */
    private function setLimit($limit)
    {
        Assertion::nullOrNumeric($limit, 'The "limit" field must be a number', 'Search limit');
        Assertion::nullOrMin($limit, self::SEARCH_MIN_LIMIT, 'The "limit" field must be a number greater than 1', 'Search limit');

        $this->limit = $limit;
    }
}
