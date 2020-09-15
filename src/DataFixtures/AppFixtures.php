<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ad = new Ad();

        $ad->setTitle("titre de l\'annonce")
            ->setSlug("titre-de-l-annonce")
            ->setCoverImage("http://placehold.it/1000x300")
            ->setIntroduction("this is an amazing introduction")
            ->setContent("<p>Hi, i'm very rich content</p>")
            ->setPrice(80)
            ->setRooms(3);

        $manager->persist($ad);
        $manager->flush();
    }
}
