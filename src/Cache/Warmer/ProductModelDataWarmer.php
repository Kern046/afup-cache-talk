<?php

declare(strict_types=1);

namespace App\Cache\Warmer;

use App\Repository\ProductModelRepository;
use App\Service\ProductModelDataServiceInterface;
use Doctrine\Common\Collections\Criteria;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

final readonly class ProductModelDataWarmer implements CacheWarmerInterface
{
    public function __construct(
        private ProductModelRepository $productModelRepository,
        private ProductModelDataServiceInterface $productModelDataService,
        private Stopwatch $stopwatch,
        private LoggerInterface $logger,
    ) {
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        $this->stopwatch->start('process-product-models-data', 'app');

        foreach ($this->productModelRepository->matching(new Criteria()) as $model) {
            $this->productModelDataService->getData($model);
        }

        $this->stopwatch->stop('process-product-models-data');

        $this->logger->info('Product model data warmed up in {duration}ms', [
            'duration' => $this->stopwatch->getEvent('process-product-models-data')->getDuration(),
        ]);

        return [];
    }

    public function isOptional(): bool
    {
        return false;
    }
}