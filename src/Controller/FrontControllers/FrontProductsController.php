<?php

namespace App\Controller\FrontControllers;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\CartProducts;
use App\Service\CartService;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontProductsController extends AbstractController
{
    /**
     * 
     * @Route("category_products/{category}", name="products_list", requirements={"category"="\d+"})
     */
    public function showProducts(int $category, CategoryRepository $categoryRepository)
    {
        $cat = $categoryRepository->find($category);

        return $this->render('front/products_list.html.twig', [
            'category' => $cat,
            'products' => $cat->getProducts()
        ]);
    }

    /**
     * @Route("category_products/product/{id}", name="category_product_show")
     */
    public function show(Product $product, CartService $cartService, Request $request, CartRepository $cartRepository): Response
    {
        if($request->get('submit'))
        {
            $cartService->addProductToCart($request, $product, $cartRepository);

            $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    return $object->getId();
                },
            ];
            // return $this->json($result, 200, [], $defaultContext);
            return $this->json($cartService->getCart(), 200, [], $defaultContext);
        }

        return $this->render('front/show_product.html.twig', [
            'product' => $product,
            'session' => $cartService->getSession()
        ]);
    }

    /**
     * @Route("/cart/new_product", name="product_added")
     */
    public function addedProduct(Product $product)
    {
        return $this->render('front/product_added', [ 'product' => $product]);
    }

    /**
     * @Route("/cart/remove_product/{id}", name="remove_product")
     */
    public function removeProduct(CartService $cartService, CartProducts $cartProduct)
    {
        $cartService->removeProductToCart($cartProduct);
        return $this->redirectToRoute('accueil_path');
    }
}