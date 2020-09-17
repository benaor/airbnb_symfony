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

    /**
     * For display a specific Ad
     * 
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show($slug, AdRepository $adsRepository)
    {
        //retrieve the ad that matches the slug
        $ad = $adsRepository->findOneBySlug($slug);

        //Return template
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
