<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager)
    {

        $ads        = $manager->createQuery("SELECT COUNT(a) FROM App\Entity\Ad a")->getSingleScalarResult();
        $bookings   = $manager->createQuery("SELECT COUNT(b) FROM App\Entity\Booking b")->getSingleScalarResult();
        $comments   = $manager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c")->getSingleScalarResult();
        $users      = $manager->createQuery("SELECT COUNT(u) FROM App\Entity\User u")->getSingleScalarResult();

        $bestAds    = $manager->createQuery(
            "SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note DESC"
        )->setMaxResults(5)
        ->getResult();

        $badAds    = $manager->createQuery(
            "SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ASC"
        )->setMaxResults(5)
        ->getResult();

        dump($bestAds);

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('ads', 'bookings', 'comments', 'users'),
            'bestAds' => $bestAds,
            'badAds' => $badAds
        ]);
    }
}
