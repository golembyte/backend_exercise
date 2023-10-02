<?php

namespace App\Application\Beer;

use App\Domain\Beer\Beer;
use App\Domain\Beer\Service\BeerServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BeerShowService
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
     * @param BeerShowRequest $request
     * @return Beer
     * @throws BeerServiceInterface
     */
    public function execute(BeerShowRequest $request)
    {
        /** @var Beer $beer */
        if (!($beer = $this->beerService->find($request->get('id')))) {
            throw new NotFoundHttpException();
        }

        return $beer;
    }
}
