<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking, [
            'validation_groups' => ["default", "front"]
        ]);

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {

            $booking->setBooker($this->getUser())
            ->setAd($ad);
            
            if ($booking->isBookabledate() == false) {

                $this->addFlash('danger', "Les dates que vous avez choisis ne sont pas disponible.");

            } else {

                $manager->persist($booking);
                $manager->flush();
    
                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), "withAlert" => true ]);
                
            }
            
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Display details of the specific booking
     * 
     * @Route("/booking/{id}", name="booking_show")
     *
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return response
     */
    public function show(Booking $booking, Request $request, EntityManagerInterface $manager){

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                "success",
                "Votre commentaire a bien été enregistré."
            );

        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]); 
    }
}
