<?php

namespace App\Controller;

use App\Entity\Ad;
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
     * For create new Ads
     * @Route("/ads/new", name="ads_new")
     */
    public function newAd()
    {
        $ad = new Ad();
        $form = $this->createFormBuilder($ad,)
            ->add('title')
            ->add('introduction')
            ->add('content')
            ->add('rooms')
            ->add('price')
            ->add('coverImage')
            ->getForm();

        //Return template
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * For display a specific Ad
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {
        //Return template
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}
