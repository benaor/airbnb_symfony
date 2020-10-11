<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/account", name="admin_account")
     */
    public function index()
    {
        return $this->render('admin/account/index.html.twig', [
            'controller_name' => 'AdminAccountController',
        ]);
    }
}
