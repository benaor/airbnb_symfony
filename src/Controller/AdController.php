<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $adsRepository)
    {
        //retrieve Ad Repository
        $ads  = $adsRepository->findAll();

        //Return template
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
}
