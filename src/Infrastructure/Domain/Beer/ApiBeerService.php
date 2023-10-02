<?php

namespace App\Infrastructure\Domain\Beer;

use App\Domain\Beer\Service\BeerServiceInterface;
use App\Infrastructure\Domain\Api\ApiService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiBeerService extends ApiService implements BeerServiceInterface {

    protected static $entityClassName = 'beers';

    /**
     * @return mixed
     */
    public function search(array $queryParameters = []) : array
    {
        $response = $this->get(self::$entityClassName, $queryParameters);

        $data = $response->toArray();

        return $data;
    }

    /**
     * @return mixed
     */
    public function find(int $id) : array
    {
        $response = $this->get(self::$entityClassName . '/' . $id);
        if($response->getStatusCode() !== 200) {
            throw new NotFoundHttpException();
        }
        $data = $response->toArray();

        return $data[0];
    }
}