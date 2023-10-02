<?php

namespace App\Tests\Application\Beer;

use App\Application\Beer\BeerIndexService;
use App\Application\Beer\BeerIndexRequest;
use App\Domain\Beer\Service\BeerServiceInterface;
use PHPUnit\Framework\TestCase;

class BeerIndexServiceTest extends TestCase
{
    private $mockBeerService;

    private $beerIndexService;

    protected function setUp(): void
    {
        $this->mockBeerService = $this->createMock(BeerServiceInterface::class);

        $this->beerIndexService = new BeerIndexService($this->mockBeerService);
    }

    public function testExecuteWithDefaults()
    {
        $this->mockBeerService->expects($this->once())
            ->method('search')
            ->with([
                'food' => 'sushi', // El valor predeterminado de food
                'page' => BeerServiceInterface::DEFAULT_SEARCH_PAGE,
                'per_page' => BeerServiceInterface::DEFAULT_SEARCH_MIN_LIMIT,
            ])
            ->willReturn(['result_data']); // Datos simulados del servicio de cerveza

        $request = new BeerIndexRequest('sushi', BeerServiceInterface::DEFAULT_SEARCH_PAGE, BeerServiceInterface::DEFAULT_SEARCH_MIN_LIMIT);
        $result = $this->beerIndexService->execute($request);

        $this->assertEquals(['result_data'], $result);
    }

    public function testExecuteWithCustomValues()
    {
        $this->mockBeerService->expects($this->once())
            ->method('search')
            ->with([
                'food' => 'pizza', // Valor personalizado para food
                'page' => 2, // Valor personalizado para page
                'per_page' => 20, // Valor personalizado para per_page
            ])
            ->willReturn(['custom_data']); // Datos simulados del servicio de cerveza

        $request = new BeerIndexRequest('pizza', 2, 20);
        $result = $this->beerIndexService->execute($request);

        $this->assertEquals(['custom_data'], $result);
    }
}