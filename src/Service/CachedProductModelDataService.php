<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\ProductModel;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(ProductModelDataService::class)]
readonly class CachedProductModelDataService implements ProductModelDataServiceInterface
{
    public function __construct(
        #[AutowireDecorated]
        protected ProductModelDataServiceInterface $innerService,
        private CacheInterface $cache,
    ) {
    }


    public function getData(ProductModel $model): array
    {
        $cacheKey = 'product_model_data_' . $model->id->toBase32();

        return $this->cache->get(
            $cacheKey,
            fn () => $this->innerService->getData($model),
        );
    }
}