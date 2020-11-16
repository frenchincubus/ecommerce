<?php

namespace App\Controller\FrontControllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil_path")
     */
    public function index(Request $request)
    {
        return $this->render('front/home.html.twig');
    }
}