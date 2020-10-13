<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong>{$ad->getTitle()} </strong> a bien été modifié"
            );
        }

        return $this->render("admin/ad/edit.html.twig", [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * For delete an Ad
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Ad $ad)
    {

        if (count($ad->getBookings()) > 0) {

            $this->addFlash(
                "warning",
                "Vous ne pouvez pas supprimer cette annonce car elle possède déjà des réservations"
            );
            
        } else {
            $title = $ad->getTitle();
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong>{$title}</strong> a bien été supprimé"
            );
        }
        return $this->redirectToRoute("admin_ads_index");
    }
}
