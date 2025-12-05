<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\ProductModel;
use App\Repository\DiscountRepository;
use App\Repository\ProductRepository;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsAlias(ProductModelDataServiceInterface::class)]
readonly class ProductModelDataService implements ProductModelDataServiceInterface
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected DiscountRepository $discountRepository,
        protected Stopwatch $stopwatch,
    ) {

    }

    public function getData(ProductModel $model): array
    {
        $this->stopwatch->lap('process-product-models-data');

        $statePercentageModifier = $this->calculateStatePercentageModifier($model);

        $basePrice = $this->calculateBasePrice($model, $statePercentageModifier);

        $discountCoeff = $this->calculateDiscountCoeff($model);

        $price = $this->calculateFinalPrice($basePrice, $discountCoeff);

        return [
            'model' => $model,
            'origin_price' => $model->rentPrice,
            'price' => $price,
            'discount' => $discountCoeff * 100,
            'state_percentage_modifier' => $statePercentageModifier,
        ];
    }

    /**
     * @return float percentage modifier of the worst state available for the given `ProductModel`
     */
    private function calculateStatePercentageModifier(ProductModel $model): float
    {
        return min(...array_map(
            fn ($stateData) => $stateData['state']->getPricePercentageModifier(),
            array_filter(
                $this->productRepository->countProductsByModelAndState($model),
                fn ($stateData) => $stateData['count'] > 0,
            ),
        ));
    }

    private function calculateBasePrice(ProductModel $model, float $statePercentageModifier): float
    {
        return $model->rentPrice + ($model->rentPrice * ($statePercentageModifier / 100));
    }

    private function calculateDiscountCoeff(ProductModel $model): float
    {
        $discount = $this->discountRepository->findHighestActiveDiscountForModel($model);

        return (null !== $discount) ? $discount->percentage / 100 : 0;
    }

    private function calculateFinalPrice(float $basePrice, float $discountCoeff): float
    {
        return $basePrice - ($basePrice * $discountCoeff);
    }
}