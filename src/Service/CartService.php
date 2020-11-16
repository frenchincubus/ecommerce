<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Entity\Cart;
use App\Entity\CartProducts;
use App\Entity\Product;
use App\Repository\CartRepository;

class CartService extends AbstractController
{
    private $session;
    private $cart;
    private $em;
    private $cartRepository;

    public function __construct(SessionInterface $session, EntityManagerInterface $em, CartRepository $cartRepository)
    {
        $this->session = $session;        
        $this->em = $em;
        $this->cartRepository = $cartRepository;
        if ( !($this->session->get('cart_id')))
        {
            $this->createCart();
        }
    }

    public function createCart()
    {
        $this->cart = new Cart();
        $this->cart->setTotalPrice(0.00);
        $this->em->persist($this->cart);
        $this->em->flush();
        $this->session->set('cart_id', $this->cart->getId());
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getCart()
    {
        
        return $this->cartRepository->find($this->session->get('cart_id'));
    }

    
    public function addProductToCart(Request $request, Product $product, CartRepository $cartRepository)
    {
        $quantity = $request->get('quantity');
        $setQty = false;
        $cart = $cartRepository->find($this->session->get('cart_id'));

        foreach($cart->getCartProducts() as $cartProduct){
            if($cartProduct->getProduct() === $product) {
                $cartProduct->setQuantity($cartProduct->getQuantity() + $quantity);
                $setQty = true;
            }
        }

        if(!$setQty){
            $cartProduct = new CartProducts();
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity($quantity);
            $cartProduct->setDate(new \DateTime());
            $cart->addCartProduct($cartProduct);
            $cart->setTotalPrice();
            $this->em->persist($cartProduct);
        }
        
        $this->em->flush();
    }


    public function removeProductToCart(CartProducts $cartProduct)
    {
        $cart = $this->getCart();
        $cart->removeCartProduct($cartProduct);
        $cart->setTotalPrice();
        $this->em->flush();
    }

}
