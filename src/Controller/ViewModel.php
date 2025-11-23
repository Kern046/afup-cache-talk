<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductModelRepository;
use App\Service\ProductModelDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ViewModel extends AbstractController
{
    #[Route(
        '/products/{modelSlug}',
        name: 'view_product_model',
    )]
    public function __invoke(
        ProductModelRepository $productModelRepository,
        ProductModelDataService $productModelDataService,
        string $modelSlug,
    ): Response {
        $model = $productModelRepository->findOneBy(['slug' => $modelSlug])
            ?? throw $this->createNotFoundException('Model not found');

        return $this->render('products/view.html.twig', [
            'model' => $model,
            'data' => $productModelDataService->getData($model),
        ]);
    }
}