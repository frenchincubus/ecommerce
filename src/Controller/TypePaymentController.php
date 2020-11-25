<?php

namespace App\Controller;

use App\Entity\TypePayment;
use App\Form\TypePaymentType;
use App\Repository\TypePaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/type/payment")
 */
class TypePaymentController extends AbstractController
{
    /**
     * @Route("/", name="type_payment_index", methods={"GET"})
     */
    public function index(TypePaymentRepository $typePaymentRepository): Response
    {
        return $this->render('type_payment/index.html.twig', [
            'type_payments' => $typePaymentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_payment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typePayment = new TypePayment();
        $form = $this->createForm(TypePaymentType::class, $typePayment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typePayment);
            $entityManager->flush();

            return $this->redirectToRoute('type_payment_index');
        }

        return $this->render('type_payment/new.html.twig', [
            'type_payment' => $typePayment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_payment_show", methods={"GET"})
     */
    public function show(TypePayment $typePayment): Response
    {
        return $this->render('type_payment/show.html.twig', [
            'type_payment' => $typePayment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_payment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypePayment $typePayment): Response
    {
        $form = $this->createForm(TypePaymentType::class, $typePayment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_payment_index');
        }

        return $this->render('type_payment/edit.html.twig', [
            'type_payment' => $typePayment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_payment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypePayment $typePayment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typePayment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typePayment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_payment_index');
    }
}
