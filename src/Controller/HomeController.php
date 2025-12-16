<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product\ProductModel;
use App\Repository\DiscountRepository;
use App\Repository\ProductModelRepository;
use App\Service\ProductModelDataServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Stopwatch\Stopwatch;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function __invoke(
        DiscountRepository $discountRepository,
        ProductModelRepository $productModelRepository,
        ProductModelDataServiceInterface $productModelDataService,
        Stopwatch $stopwatch,
    ): Response {
        $stopwatch->start('find-product-models', 'app');

        $productModels = $productModelRepository->findAll();

        $stopwatch->stop('find-product-models');

        $stopwatch->start('process-product-models-data', 'app');

        $models = array_map(
            fn(ProductModel $model) => $productModelDataService->getData($model),
            $productModels,
        );

        $stopwatch->stop('process-product-models-data');

        return $this->render(
            'home/index.html.twig',
            [
                'models' => $models,
                'active_discounts' => $discountRepository->getAllActiveDiscounts(),
            ],
            response: new Response()
                ->setCache([
                    'max_age' => 0,
                    's_maxage' => 60,
                    'stale_while_revalidate' => 30,
                    'stale_if_error' => 600,
                ]),
        );
    }
}