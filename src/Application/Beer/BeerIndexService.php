<?php

namespace App\Application\Beer;

use App\Domain\Beer\Service\BeerServiceInterface;

class BeerIndexService
{
    /**
     * @var BeerServiceInterface
     */
    private $beerService;

    /**
     * @param BeerServiceInterface $beerService
     */
    public function __construct(BeerServiceInterface $beerService)
    {
        $this->beerService = $beerService;
    }

    /**
     * @return mixed
     */
    public function execute(BeerIndexRequest $request)
    {
        $queryParameters = [
            'food' => $request->get('food'),
            'page' =>  $request->get('page') ?? BeerServiceInterface::DEFAULT_SEARCH_PAGE,
            'per_page' =>  $request->get('limit') ?? BeerServiceInterface::DEFAULT_SEARCH_MIN_LIMIT,
        ];
        return $this->beerService->search(
            $queryParameters
        );
    }
}