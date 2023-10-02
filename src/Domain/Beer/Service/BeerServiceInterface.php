<?php

namespace App\Domain\Beer\Service;

interface BeerServiceInterface {

    const DEFAULT_SEARCH_PAGE = 1;
    const DEFAULT_SEARCH_MIN_LIMIT = 20;

    /**
     * @return mixed
     */
    public function search(array $queryParameters): array;
    public function find(int $id): array;
}