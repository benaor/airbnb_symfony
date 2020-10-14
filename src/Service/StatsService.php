<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $ads        = $this->getAdsCount();
        $bookings   = $this->getBookingsCount();
        $comments   = $this->getCommentsCount();
        $users      = $this->getUsersCount();

        return compact('ads', 'bookings', 'comments', 'users');
    }

    public function getAdsCount()
    {
        return $this->manager->createQuery("SELECT COUNT(a) FROM App\Entity\Ad a")->getSingleScalarResult();
    }

    public function getBookingsCount()
    {
        return $this->manager->createQuery("SELECT COUNT(b) FROM App\Entity\Booking b")->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c")->getSingleScalarResult();
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery("SELECT COUNT(u) FROM App\Entity\User u")->getSingleScalarResult();
    }

    public function getAdsStats($direction, $limit)
    {
        return $this->manager->createQuery(
            "SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ".$direction
        )->setMaxResults($limit)
            ->getResult();
    }
}
