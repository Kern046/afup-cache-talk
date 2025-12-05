<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\ProductModel;

interface ProductModelDataServiceInterface
{
    public function getData(ProductModel $model): array;
}