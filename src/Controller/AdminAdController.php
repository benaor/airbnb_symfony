<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $adRepo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $adRepo->findAll()
        ]);
    }

    /**
     * For Admin can edit an Ad
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad){
        $form = $this->createForm(AdType::class, $ad);
        
        return $this->render("admin/ad/edit.html.twig", [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }
}
