<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        //Manage they User
        $users = [];

        for ($i=1; $i < 10; $i++) { 
            $user = new User();

            $user->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash('password')
                ;

            $manager->persist($user);
            $users[] = $user;
        }

        //Manage they Ads
        for ($i = 1; $i < 40; $i++) {

            //new Annonce
            $ad = new Ad();

            //create faker data
            $title          = $faker->sentence();
            $coverImage     = $faker->imageUrl(1000, 350);
            $introduction   = $faker->paragraph(2);
            $content        = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $user           = $users[mt_rand(0, count($users) -1)];

            //Add property of this ad
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(20, 500))
                ->setRooms(mt_rand(0, 8))
                ->setAuthor($user);

            for ($j=1; $j < mt_rand(2, 5); $j++) { 
                $image      = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                    $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
