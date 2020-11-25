<?php

namespace App\Controller\FrontControllers;

use App\Entity\Order;
use App\Entity\Payment;
use App\Service\CartService;
use App\Repository\AddressRepository;
use App\Repository\CarrierRepository;
use App\Repository\TypePaymentRepository;
use App\Repository\WeightRangeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderTunnelController extends AbstractController
{
    /**
     * @Route("/order/tunnel", name="order_tunnel")
     */
    public function index(CartService $cartService, CarrierRepository $carrierRepo)
    {
        $cart = $cartService->getCart();
        $totalWeight = 0.0;
        $carriers = $carrierRepo->findAll();
        foreach ($cart->getCartProducts() as $cartProduct) {
            $totalWeight += ($cartProduct->getProduct()->getWeight() * $cartProduct->getQuantity());
        }
        return $this->render('order_tunnel/index.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/order/tunnel/adresses", name="order_tunnel_addresses")
     */
    public function adresses(Request $request, SessionInterface $session)
    {
        $adresse = $request->get('adresse');
        $customer = $this->getUser();

        if ($request->get('submit') && !is_null($adresse))
        {
            $session->set('adresse', $adresse);
            return $this->redirectToRoute('order_tunnel_carriers');
        }

        return $this->render('order_tunnel/adresses.html.twig', [
            'addresses' => $customer->getAddresses()
        ]);
    }

    /**
     * @Route("/order/tunnel/carriers", name="order_tunnel_carriers")
     */
    public function carrier(Request $request, SessionInterface $session, CartService $cartService,  CarrierRepository $carrierRepo)
    {
        $cart = $cartService->getCart();
        $totalWeight = 0.0;
        $carriers = $carrierRepo->findAll();
        foreach ($cart->getCartProducts() as $cartProduct) {
            $totalWeight += ($cartProduct->getProduct()->getWeight() * $cartProduct->getQuantity());
        }

        if ($request->get('submit') && $request->get('carrier_range'))
        {
            $session->set('carrier_range', $request->get('carrier_range'));
            return $this->redirectToRoute('order_tunnel_payments');
        } else
        {
            $this->addFlash('notice', 'Please select a carrier');
        }
        return $this->render('order_tunnel/carriers.html.twig', [
            'carriers' => $carriers,
            'weight' => $totalWeight
        ]);
    }

    /**
     * @Route("order/tunnel/payments", name="order_tunnel_payments")
     */
    public function payments(Request $request, TypePaymentRepository $typePaymentRepo, SessionInterface $session)
    {
        $payments = $typePaymentRepo->findAll();

        if ($request->get('submit') && $request->get('payment'))
        {
            $session->set('payment', $request->get('payment'));
            return $this->redirectToRoute('order_tunnel_summary');
        } else
        {
            $this->addFlash('notice', 'Please select a payment');
        }
        return $this->render('order_tunnel/payments.html.twig', [
            'payments' => $payments
        ]);
    }

    /**
     * @Route("order/tunnel/summary", name="order_tunnel_summary")
     */
    public function summary(Request $request, SessionInterface $session, CartService $cartService, AddressRepository $adresseRepo, WeightRangeRepository $weightRangeRepo, TypePaymentRepository $typePaymentRepo)
    {
        $customer = $this->getUser();
        $cart = $cartService->getCart();
        $address = $adresseRepo->find((int)$session->get('adresse'));
        $carrier = $weightRangeRepo->find((int)$session->get('carrier_range'));
        $payment = $typePaymentRepo->find((int)$session->get('payment'));
        $vat = 0;

        if ($request->get('submit'))
        {
            if ($cart->getCustomerId() === null)
            {
                $cart->setCustomerId($customer);
            }
            // Set new Payment
            $newPayment = new Payment();
            $newPayment->setCustomer($customer);
            $newPayment->setTypePayment($payment);
            $newPayment->setName($payment->getName().'payment');
            $newPayment->setDate(new \Datetime());
            $newPayment->setAmount($cart->getTotalPrice() + $carrier->getPrice());

            // Set new Order from submitted cart and delivery infos
            $order = new Order();
            $order->setCartId($cart);
            $order->setCarrier($carrier);
            $order->setPayment($newPayment);
            $order->setDate(new \DateTime());
            $order->setState(1);
            $order->setAmount($cart->getTotalPrice() + $carrier->getPrice());
            $order->setShipping($carrier->getPrice());

            // UpdateQuantity for each product
            foreach ($cart->getCartProducts() as $cartProduct) {
                $product = $cartProduct->getProduct();
                $productQuantity = $product->getQuantity();
                $productQuantity->setQuantity($productQuantity->getQuantity() - $cartProduct->getQuantity());
                $vat += ($product->getPrice() * $product->getVat()->getVat() * $cartProduct->getQuantity());
            }

            $order->setVat($vat);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('order_tunnel_confirm');
        }

        return $this->render('order_tunnel/summary.html.twig', [
            'cart' => $cart,
            'addresse' => $address,
            'carrier' => $carrier,
            'payment' => $payment
        ]);
    }

    /**
     * @Route("order/tunnel/confirmation", name="order_tunnel_confirm")
     */
    public function confirmation(SessionInterface $session)
    {
        $session->remove('cart_id');
        $session->remove('carrier_range');
        $session->remove('payment');
        $session->remove('adresse');

        return $this->render('order_tunnel/confirmation.html.twig');
    }
}
