<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\Product;
use App\Repository\ProductRepository;

readonly class ProductSearchService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @return list<Product>
     */
    public function search(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
    ): array {
        $products = $this->productRepository->searchAvailableProducts(
            $startDate,
            $endDate,
        );


    }
}