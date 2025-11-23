<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\ProductModel;
use App\Entity\Product\ProductState;
use App\Repository\DiscountRepository;
use App\Repository\ProductModelRepository;
use App\Repository\ProductRepository;

readonly class ProductModelDataService
{
    public function __construct(
        private ProductRepository $productRepository,
        private DiscountRepository $discountRepository,
    ) {

    }

    public function getData(ProductModel $model): array
    {
        $stats = $this->productRepository->countProductsByState();

        $statePercentageModifier = min(...array_map(
            fn ($stateData) => $stateData['state']->getPricePercentageModifier(),
            array_filter(
                $stats,
                fn ($stateData) => $stateData['count'] > 0,
            ),
        ));

        $discount = $this->discountRepository->findHighestActiveDiscountForModel($model);

        $discountCoeff = (null !== $discount)
            ? $discount->percentage / 100
            : 0;

        $basePrice = $model->rentPrice + ($model->rentPrice * ($statePercentageModifier / 100));
        $price = round(
            $basePrice - $basePrice * $discountCoeff,
            2,
        );

        return [
            'model' => $model,
            'origin_price' => $model->rentPrice,
            'price' => $price,
            'discount' => $discount,
            'state_percentage_modifier' => $statePercentageModifier,
        ];
    }
}