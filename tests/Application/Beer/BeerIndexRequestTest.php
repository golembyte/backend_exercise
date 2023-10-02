<?php

namespace App\Tests\Application\Beer;

use App\Application\Beer\BeerIndexService;
use App\Application\Beer\BeerIndexRequest;
use App\Domain\Beer\Service\BeerServiceInterface;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class BeerIndexRequestTest extends TestCase
{
    public function testValidRequest()
    {
        $food = 'pizza';
        $page = 2;
        $limit = 10;

        $request = new BeerIndexRequest($food, $page, $limit);

        $this->assertInstanceOf(BeerIndexRequest::class, $request);
        $this->assertEquals($food, $request->get('food'));
        $this->assertEquals($page, $request->get('page'));
        $this->assertEquals($limit, $request->get('limit'));
    }

    public function testEmptyFood()
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('The "food" field is required');

        $food = '';
        $page = 2;
        $limit = 10;

        new BeerIndexRequest($food, $page, $limit);
    }

    public function testNonNumericPage()
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('The "page" field must be a number');

        $food = 'pizza';
        $page = 'invalid';
        $limit = 10;

        new BeerIndexRequest($food, $page, $limit);
    }

    public function testNegativePage()
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('The "page" field must be a number greater than 1');

        $food = 'pizza';
        $page = -1;
        $limit = 10;

        new BeerIndexRequest($food, $page, $limit);
    }

    public function testNonNumericLimit()
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('The "limit" field must be a number');

        $food = 'pizza';
        $page = 2;
        $limit = 'invalid';

        new BeerIndexRequest($food, $page, $limit);
    }

    public function testNegativeLimit()
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage('The "limit" field must be a number greater than 1');

        $food = 'pizza';
        $page = 2;
        $limit = -1;

        new BeerIndexRequest($food, $page, $limit);
    }
}