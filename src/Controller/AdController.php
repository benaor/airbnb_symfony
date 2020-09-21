<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function newAd(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);

        //Manage the HTTP Request in connection with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush(); 

            $this->addFlash(
                'success', 
                "l'annonce <strong>".$ad->getTitle()."</strong> à bien été enregistrer !"
            ); 

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug() 
            ]);
        }

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
