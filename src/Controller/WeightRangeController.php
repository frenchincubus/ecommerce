<?php

namespace App\Controller;

use App\Entity\WeightRange;
use App\Form\WeightRangeType;
use App\Repository\WeightRangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/weight/range")
 */
class WeightRangeController extends AbstractController
{
    /**
     * @Route("/", name="weight_range_index", methods={"GET"})
     */
    public function index(WeightRangeRepository $weightRangeRepository): Response
    {
        return $this->render('weight_range/index.html.twig', [
            'weight_ranges' => $weightRangeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="weight_range_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $weightRange = new WeightRange();
        $form = $this->createForm(WeightRangeType::class, $weightRange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($weightRange);
            $entityManager->flush();

            return $this->redirectToRoute('weight_range_index');
        }

        return $this->render('weight_range/new.html.twig', [
            'weight_range' => $weightRange,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="weight_range_show", methods={"GET"})
     */
    public function show(WeightRange $weightRange): Response
    {
        return $this->render('weight_range/show.html.twig', [
            'weight_range' => $weightRange,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="weight_range_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WeightRange $weightRange): Response
    {
        $form = $this->createForm(WeightRangeType::class, $weightRange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('weight_range_index');
        }

        return $this->render('weight_range/edit.html.twig', [
            'weight_range' => $weightRange,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="weight_range_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WeightRange $weightRange): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weightRange->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($weightRange);
            $entityManager->flush();
        }

        return $this->redirectToRoute('weight_range_index');
    }
}
