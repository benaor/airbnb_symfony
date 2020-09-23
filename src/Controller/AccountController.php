<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * For user login
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login()
    {
        return $this->render('account/login.html.twig');
    }

    /**
     * For user logout
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return Response
     */
    public function logout()
    {
    }
}
