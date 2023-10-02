<?php

namespace App\Tests\Application\Beer;

use App\Application\Beer\BeerIndexService;
use App\Application\Beer\BeerIndexRequest;
use App\Application\Beer\BeerShowRequest;
use App\Domain\Beer\Service\BeerServiceInterface;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class BeerShowRequestTest extends TestCase
{
    public function testValidId()
    {
        // Create a valid ID
        $id = 1;

        // Instantiate the BeerShowRequest with the valid ID
        $request = new BeerShowRequest($id);

        // Assert that the ID property is correctly set
        $this->assertEquals($id, $request->get('id'));
    }

    public function testEmptyId()
    {
        // Create an empty ID
        $id = null;

        // Expect an AssertionFailedException to be thrown
        $this->expectException(\Assert\AssertionFailedException::class);

        // Instantiate the BeerShowRequest with the empty ID
        $request = new BeerShowRequest($id);
    }
}