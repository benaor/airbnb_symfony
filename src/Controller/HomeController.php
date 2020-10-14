<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homePage")
     */
    public function index(AdRepository $adRepository, UserRepository $userRepository)
    {
        return $this->render('home/home.html.twig', [
            'ads' => $adRepository->findBestAds(3),
            'users' => $userRepository->findBestUser(2)
        ]);
    }
}
