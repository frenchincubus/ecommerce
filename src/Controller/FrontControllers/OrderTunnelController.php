<?php

namespace App\Controller\FrontControllers;

use App\Service\CartService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderTunnelController extends AbstractController
{
    /**
     * @Route("/order/tunnel", name="order_tunnel")
     */
    public function index(CartService $cartService)
    {
        $cart = $cartService->getCart();
        return $this->render('order_tunnel/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    
}
