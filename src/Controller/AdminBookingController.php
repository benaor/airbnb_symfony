<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking", name="admin_booking_index")
     */
    public function index(BookingRepository $booking)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $booking->findAll()
        ]);
    }

    /**
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking)
    {
        $form = $this->createForm(BookingType::class, $booking);

        return $this->render('admin/booking/edit.html.twig', [
            'bookings' => $booking,
            'form' => $form->createView()
        ]);
    }


}
