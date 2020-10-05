<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Role;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $AdminRole = new Role(); 
        $AdminRole->setTitle('ROLE_ADMIN');
        $manager->persist($AdminRole);

        $adminUser = new User();
        $adminUser->setFirstName('benjamin')
                ->setLastName('GIRARD')
                ->setEmail('benjamin@girard.com')
                ->setHash($this->passwordEncoder->encodePassword($adminUser, 'password'))
                ->setPicture('https://randomuser.me/api/portraits/men/5.jpg')
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->addUserRole($AdminRole);
        $manager->persist($adminUser);




        //Manage they User
        $users  = [];
        $genres = ['male', 'female'];

        for ($k = 1; $k < 10; $k++) {
            $user = new User();

            //Variable for picture
            $genre           = $faker->randomElement($genres);
            $sexe            = $genre == "male" ? 'men' : 'women';
            $avatarPictureId = mt_rand(0, 99);
            $picture         = 'https://randomuser.me/api/portraits/' . $sexe . '/' . $avatarPictureId . '.jpg';

            //variable for passwordEncode
            $encoded         = $this->passwordEncoder->encodePassword($user, 'password');
            
            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setPicture($picture)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($encoded);

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
            $user           = $users[mt_rand(0, count($users) - 1)];

            //Add property of this ad
            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(20, 500))
                ->setRooms(mt_rand(0, 8))
                ->setAuthor($user);

            for ($j = 1; $j < mt_rand(2, 5); $j++) {
                $image      = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // Management of bookings 
            for ($r=1; $r < mt_rand(1, 10); $r++) { 

                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 mounths');
                $startDate = $faker->dateTimeBetween('-3mounths');
                $duration  = mt_rand(3, 10); 
                $endDate   = (clone $startDate)->modify("+ $duration days");
                $amount    = $ad->getPrice() * $duration; 
                $booker    = $users[mt_rand(0, count($users) -1 )];
                $comment   = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);
                        

                $manager->persist($booking);

                if (mt_rand(0, 1)) {
                    $comment = new Comment();

                    $createdAt = $faker->dateTimeBetween('-6 mounths');

                    $comment->setCreatedAt($createdAt)
                            ->setRating(mt_rand())
                            ->setContent($faker->paragraph())
                            ->setAuthor($booker)
                            ->setAd($ad);

                    $manager->persist($comment);
                }
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
