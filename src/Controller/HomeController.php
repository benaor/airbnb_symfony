<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homePage")
     */
    public function index(AdRepository $adRepository)
    {
        return $this->render('home/home.html.twig', [
            'ads' => $adRepository->findBestAds(3)
        ]);
    }
}
