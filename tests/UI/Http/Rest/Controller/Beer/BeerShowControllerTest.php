<?php

namespace App\Tests\UI\Http\Rest\Controller\Beer;

use App\Application\Beer\BeerIndexService;
use App\Application\Beer\BeerShowService;
use App\UI\Http\Rest\Controller\Beer\BeerIndexController;
use App\UI\Http\Rest\Controller\Beer\BeerShowController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Cache\CacheInterface;


class BeerShowControllerTest extends WebTestCase
{
    private $mockBeerShowService;

    public function setUp(): void
    {
        $this->mockBeerShowService = $this->createMock(BeerShowService::class);
    }

    public function testShowShouldReturnSuccess()
    {
        $beer =[
            'id' => 2,
            'name' => 'Beer 2',
            'tagline' => 'Tagline 2',
            'first_brewed' => '2021-01-02',
            'description' => 'Description 2',
            'image_url' => 'https://example.com/beer2.jpg',
        ];

        $controller = new BeerShowController($this->mockBeerShowService, $this->getCacheMock());

        $response = $controller->show(2);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('beer', $content['data']);
        $this->assertEquals($beer, $content['data']['beer']);
    }

    public function testIndexShouldReturnSuccessFromService()
    {
        $beer = [
            'id' => 2,
            'name' => 'Beer 2',
            'tagline' => 'Tagline 2',
            'first_brewed' => '2021-01-02',
            'description' => 'Description 2',
            'image_url' => 'https://example.com/beer2.jpg',
        ];

        $this->mockBeerShowService
            ->method('execute')
            ->willReturn($beer);


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

        $controller = new BeerShowController($this->mockBeerShowService, $this->getCacheMock());

        $response = $controller->show(2);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $content);
        $this->assertArrayHasKey('beer', $content['data']);
        $this->assertEquals($beer, $content['data']['beer']);
    }

    public function testIndexShouldReturnBadRequestWhenValidationFails()
    {
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

        $controller = new BeerShowController($this->mockBeerShowService, $cache);

        $response = $controller->show(0);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertEquals('No id provided.', $content['error']);
    }

    public function testIndexShouldReturnNotFoundErrorWhenBeerNotFound()
    {
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

        $this->mockBeerShowService
            ->method('execute')
            ->willThrowException(  new NotFoundHttpException());

        $controller = new BeerShowController($this->mockBeerShowService, $cache);

        $response = $controller->show(1);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertEquals('Beer not found.', $content['error']);
    }

    public function testIndexShouldReturnInternalServerErrorWhenSomethingGoesWrong()
    {
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

        $this->mockBeerShowService
            ->method('execute')
            ->willThrowException(  new \Exception());

        $controller = new BeerShowController($this->mockBeerShowService, $cache);

        $response = $controller->show(1);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertEquals('Something went wrong.', $content['error']);
    }

    private function getCacheMock(): CacheInterface
    {
        $mockCache = $this->createMock(CacheInterface::class);

        $mockCache
            ->method('get')
            ->with('beer_2')
            ->willReturnCallback(function (string $key, callable $callback) {
                return new JsonResponse(
                    [ 'data' => [
                        'beer' =>
                            [
                                'id' => 2,
                                'name' => 'Beer 2',
                                'tagline' => 'Tagline 2',
                                'first_brewed' => '2021-01-02',
                                'description' => 'Description 2',
                                'image_url' => 'https://example.com/beer2.jpg',
                            ]
                        ]
                    ]
                );
            });

        return $mockCache;
    }
}