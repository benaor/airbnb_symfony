<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        for($i=1 ; $i < 40; $i++){ 

            //new Annonce
            $ad = new Ad();
            
            //Add property of this ad
            $ad->setTitle("title number ".$i)
                ->setSlug("titre-de-l-annonce".$i)
                ->setCoverImage("http://placehold.it/1000x300")
                ->setIntroduction("this is an amazing introduction")
                ->setContent("<p>Hi, i'm very rich content</p>")
                ->setPrice(mt_rand(20, 500))
                ->setRooms(mt_rand(0, 8));
    
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
