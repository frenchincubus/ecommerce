<?php

namespace App\Controller\AdminController;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminHomeController extends AbstractController
{
    /**
     * @Route("/", name="admin_controller")
     */
    public function index()
    {
        return $this->render('admin/admin_home.html.twig');
    }
}