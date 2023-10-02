<?php

namespace App\Tests\Application\Beer;

use App\Application\Beer\BeerShowRequest;
use App\Application\Beer\BeerShowService;
use App\Domain\Beer\Beer;
use App\Domain\Beer\Service\BeerServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BeerShowServiceTest extends TestCase
{
    private $mockBeerService;

    private $beerShowService;

    protected function setUp(): void
    {

        $this->mockBeerService = $this->createMock(BeerServiceInterface::class);

        $this->beerShowService = new BeerShowService($this->mockBeerService);
    }

    public function testExecuteWithExistingBeer()
    {

        $expectedBeer =[
            'id' => 2,
            'name' => 'Beer 2',
            'tagline' => 'Tagline 2',
            'first_brewed' => '2021-01-02',
            'description' => 'Description 2',
            'image_url' => 'https://example.com/beer2.jpg',
        ];
        $this->mockBeerService->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedBeer);

        $request = new BeerShowRequest(1);
        $result = $this->beerShowService->execute($request);

        $this->assertEquals($expectedBeer, $result);
    }

    public function testExecuteWithNotFoundBeer()
    {

        $this->mockBeerService->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn([]);

        $request = new BeerShowRequest(2);

        $this->expectException(NotFoundHttpException::class);
        $this->beerShowService->execute($request);
    }
}