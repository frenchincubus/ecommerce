<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Service\CartService;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function Registration(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $customer = new Customer();

        $form = $this->createForm(RegistrationType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $customer->setRoles(['ROLE_USER']);
            $hash = $encoder->encodePassword($customer, $customer->getPassword());
            $customer->setPassword($hash);
            $manager->persist($customer);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login()
    {
        
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/customerboard/checkpassword", name="check_password")
     */
    public function checkCredentials(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $customer = $this->getUser();

        if($request->get('submit'))
        {
            if($encoder->isPasswordValid($customer, $request->get('password')))
            {
                return $this->redirectToRoute('security_new_password');
            } else {
                $this->addFlash('failure', 'mot de passe incorrect.');
                return $this->redirectToRoute('check_password');
            }
        }

        return $this->render('security/check_password.html.twig');
    }

    /**
     * @Route("/newpassword", name="security_new_password")
     */
    public function newPassword()
    {
        return $this->render('security/new_password.html.twig');
    }

    /**
     * @Route("/wiring", name="connect_to_cart")
     */
    public function connectCart(CartService $cartService, EntityManagerInterface $manager)
    {
        $customer = $this->getUser();

        if($customer) {
            $cart = $cartService->getCart();
            $cart->setCustomerId($customer);
            $manager->flush();
            return $this->redirectToRoute('customer_board');
        }

        
    }
}
