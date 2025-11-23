<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LookForRent extends AbstractController
{
    #[Route('/look-for-rent', name: 'look_for_rent')]
    public function __invoke(
        Request $request,
    ): Response {
        return $this->render('products/list.html.twig', [
            'products' => '',
        ]);
    }
}