<?php

namespace App\Controller\FrontControllers;

use App\Entity\Category;
use App\Service\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    public function renderMenu(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('front/menu.html.twig', [ 'categories' => $categories]);
    }

    public function renderHeader(CartService $cartService)
    {
        $cart = $cartService->getCart();
        return $this->render('front/header.html.twig', ['cart' => $cart]);
    }

    /**
     * @Route("/products/search/{query}", name="products_search")
     * @MaxDepth(2)
     */
    public function search(string $query, ProductRepository $productRepo): Response
    {
        $result = $productRepo->findBySearchField($query);
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        return $this->json($result, 200, [], $defaultContext);
    }
}