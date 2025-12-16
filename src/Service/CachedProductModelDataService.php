<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product\ProductModel;
use App\Enum\FeatureFlag;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsDecorator(ProductModelDataService::class)]
readonly class CachedProductModelDataService implements ProductModelDataServiceInterface
{
    public function __construct(
        #[AutowireDecorated]
        private ProductModelDataServiceInterface $innerService,
        private FeatureFlagService $featureFlagService,
        private TagAwareCacheInterface $cache,
    ) {
    }

    public function getData(ProductModel $model): array
    {
        $closure = fn() => $this->innerService->getData($model);

        if (!$this->featureFlagService->isEnabled(FeatureFlag::EnableApplicativeCache)) {
            return $closure();
        }

        $cacheKey = 'product_model_data_' . $model->id->toBase32();

        return $this->cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($closure) {
                $item->expiresAfter(3600);
                $item->tag('product_model_data');

                return $closure();
            },
        );
    }
}