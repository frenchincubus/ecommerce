<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Quantity;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategoryRepository $categoryRepo): Response
    {
        $product = new Product();
        $qt = new Quantity();
        $product->setQuantity($qt);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $qt->setQuantity((int)$form->get('qty')->getData());
            if ($form->get('category'))
            {
                foreach ($form->get('category')->getData() as $category => $value) {
                    $selectedCategory = $categoryRepo->find($value);
                    $product->addCategory($selectedCategory);
                }
            }
            $product->setTtcPrice();
            $productImages = $product->getProductImages();
            foreach($productImages as $key => $productImage){
                $productImage->setProduct($product);
                $productImages->set($key,$productImage);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, CategoryRepository $categoryRepo): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->get('qty')->setData($product->getQuantity()->getQuantity());
        $form->get('category')->setData($product->getCategories());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('qty')) {
                $product->getQuantity()->setQuantity($form->get('qty')->getData());
            }
            if ($form->has('category'))
            {
                foreach ($form->get('category')->getData() as $category => $value) {
                    $selectedCategory = $categoryRepo->find($value);
                    $product->addCategory($selectedCategory);
                }
            }
            $product->setTtcPrice();
            $productImages = $product->getProductImages();
            foreach($productImages as $key => $productImage){
                $productImage->setProduct($product);
                $productImages->set($key,$productImage);
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
