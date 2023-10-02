<?php

namespace App\Tests\UI\Http\Rest\Controller\Beer;

use App\Application\Beer\BeerIndexService;
use App\UI\Http\Rest\Controller\Beer\BeerIndexController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;


class BeerIndexControllerTest extends WebTestCase
{
    private $mockBeerIndexService;

    public function setUp(): void
    {
        $this->mockBeerIndexService = $this->createMock(BeerIndexService::class);
    }

    public function testIndexShouldReturnSuccess()
    {
        $request = new Request();
        $request->attributes->set('food', 'pizza');

        $beers =[
            'beers' => [
                'id' => 1,
                'name' => 'Beer 1',
                'tagline' => 'Tagline 1',
                'first_brewed' => '2021-01-01',
                'description' => 'Description 1',
                'image_url' => 'https://example.com/beer1.jpg',
            ],
            [
                'id' => 2,
                'name' => 'Beer 2',
                'tagline' => 'Tagline 2',
                'first_brewed' => '2021-01-02',
                'description' => 'Description 2',
                'image_url' => 'https://example.com/beer2.jpg',
            ]
        ];

        $controller = new BeerIndexController($this->mockBeerIndexService, $this->getCacheMock());

        $response = $controller->index('pizza', $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('beers', $content['data']);
        $this->assertEquals($beers, $content['data']);
    }

    public function testIndexShouldReturnSuccessFromService()
    {
        $request = new Request();
        $request->attributes->set('food', 'pizza');

        $beers =[
            [
                'id' => 1,
                'name' => 'Beer 1',
                'tagline' => 'Tagline 1',
                'first_brewed' => '2021-01-01',
                'description' => 'Description 1',
                'image_url' => 'https://example.com/beer1.jpg',
            ],
            [
                'id' => 2,
                'name' => 'Beer 2',
                'tagline' => 'Tagline 2',
                'first_brewed' => '2021-01-02',
                'description' => 'Description 2',
                'image_url' => 'https://example.com/beer2.jpg',
            ]
        ];


        $this->mockBeerIndexService
            ->method('execute')
            ->willReturn($beers);


        $cache = $this->createMock(CacheInterface::class);

        $cache
            ->method('get')
            ->willReturnCallback(function (string $key, callable $callback) {
                $item = new CacheItem(); // Create a CacheItem instance
                if (!$item->isHit()) {
                    $item->set(null); // Set the expected data here
                }
                return $callback($item);
            });

        $controller = new BeerIndexController($this->mockBeerIndexService,$cache);


        $response = $controller->index('food', $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('beers', $content['data']);
        $this->assertEquals($beers, $content['data']['beers']);
    }

    public function testIndexShouldReturnBadRequestWhenValidationFails()
    {
        $request = new Request();

        $controller = new BeerIndexController($this->mockBeerIndexService, $this->getCacheMock());

        $response = $controller->index(null, $request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertEquals('The "food" parameter is required.', $content['error']);
    }

    public function testIndexShouldReturnInternalServerErrorWhenSomethingGoesWrong()
    {
        $request = new Request();
        $request->query->set('food', 'pizza');

        $this->mockBeerIndexService
            ->method('execute')
            ->willThrowException(new \Exception('Something went wrong.'));

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willThrowException(new \Exception('Something went wrong.'));

        $controller = new BeerIndexController($this->mockBeerIndexService,$cache);

        $response = $controller->index('food', $request);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertEquals('Something went wrong.', $content['error']);
    }

    private function getCacheMock(): CacheInterface
    {
        $mockCache = $this->createMock(CacheInterface::class);

        $mockCache
            ->method('get')
            ->with(md5(serialize(['food' => 'pizza'])))
            ->willReturnCallback(function (string $key, callable $callback) {
                return new JsonResponse([
                    'data' => [
                        'beers' => [
                            'id' => 1,
                            'name' => 'Beer 1',
                            'tagline' => 'Tagline 1',
                            'first_brewed' => '2021-01-01',
                            'description' => 'Description 1',
                            'image_url' => 'https://example.com/beer1.jpg',
                        ],
                        [
                            'id' => 2,
                            'name' => 'Beer 2',
                            'tagline' => 'Tagline 2',
                            'first_brewed' => '2021-01-02',
                            'description' => 'Description 2',
                            'image_url' => 'https://example.com/beer2.jpg',
                        ]
                    ]
                ]);
            });

        return $mockCache;
    }
}