<?php

namespace App\Controller;

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
}
