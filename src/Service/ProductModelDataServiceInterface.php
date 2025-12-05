<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\ProductModel;

interface ProductModelDataServiceInterface
{
    /**
     * @return array{
     *   model: ProductModel,
     *   origin_price: int,
     *   price: int,
     *   discount: float,
     *   state_percentage_modifier: float,
     * }
     */
    public function getData(ProductModel $model): array;
}