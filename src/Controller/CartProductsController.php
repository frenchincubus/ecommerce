<?php

namespace App\Controller;

use App\Entity\CartProducts;
use App\Form\CartProducts1Type;
use App\Repository\CartProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart/products")
 */
class CartProductsController extends AbstractController
{
    /**
     * @Route("/", name="cart_products_index", methods={"GET"})
     */
    public function index(CartProductsRepository $cartProductsRepository): Response
    {
        return $this->render('cart_products/index.html.twig', [
            'cart_products' => $cartProductsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cart_products_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cartProduct = new CartProducts();
        $form = $this->createForm(CartProducts1Type::class, $cartProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cartProduct);
            $entityManager->flush();

            return $this->redirectToRoute('cart_products_index');
        }

        return $this->render('cart_products/new.html.twig', [
            'cart_product' => $cartProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_products_show", methods={"GET"})
     */
    public function show(CartProducts $cartProduct): Response
    {
        return $this->render('cart_products/show.html.twig', [
            'cart_product' => $cartProduct,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cart_products_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CartProducts $cartProduct): Response
    {
        $form = $this->createForm(CartProducts1Type::class, $cartProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cart_products_index');
        }

        return $this->render('cart_products/edit.html.twig', [
            'cart_product' => $cartProduct,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_products_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CartProducts $cartProduct): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartProduct->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cartProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_products_index');
    }
}
