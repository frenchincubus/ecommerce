<?php

namespace App\Controller\FrontControllers;

use App\Entity\Address;
use App\Entity\Customer;
use App\Form\AddressType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/customerboard")
     */
class CustomerBoardController extends AbstractController
{
    /**
     * @Route("/", name="customer_board")
     */
    public function index()
    {
        $customer = $this->getUser();
        if ($customer)
        {
            return $this->render('customer_board/index.html.twig', [
                'customer' => $customer,
            ]);
        }
        else {
            return $this->redirectToRoute('accueil_path');
        }
        
    }

    /**
     * @Route("/newadress", name="customer_new_adress")
     */
    public function addAddress(Request $request)
    {
        $customer = $this->getUser();
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setCustomerId($customer);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('customer_board');
        }

        return $this->render('customer_board/newAdress.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);

    }
}
