<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * For user login
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'error' => $error !== null,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * For user logout
     * 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout()
    {
    }

    /**
     * For display the form registration
     * 
     * @Route("/register", name="account_register")
     * 
     * @return Response
     */
    public function register(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        //Create form
        $form = $this->createForm(RegistrationType::class, $user);

        //Manage the HTTP Request in connection with the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Encode the password
            $encoded = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($encoded);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre compte a bien été créé, Nous vous souhaitons la bienvenue " . $user->getFirstName() . ".");

            return $this->redirectToRoute("account_login");
        }

        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * For edit User profil and informations
     * 
     * @Route("/account/profile", name="account_profil")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function editProfil(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Votre modifications ont bien été prises en compte, " . $user->getFirstName() . ".");

            return $this->redirectToRoute("ads_index");
        }

        return $this->render('account/profil.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * For change User password
     * 
     * @Route("/account/change-password", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function changePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Check old password and verif this
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {

                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez entré est incorrect."));
            } elseif ($passwordUpdate->getOldPassword() == $passwordUpdate->getNewPassword()) {

                $this->addFlash('danger', "Vous ne pouvez pas réutiliser le même mot de passe !");
                return $this->redirectToRoute("account_password");
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $encoded = $encoder->encodePassword($user, $newPassword);

                $user->setHash($encoded);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "votre mot de passe a bien été modifié");

                return $this->redirectToRoute("account_profil");
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * For Display Profil Of Connected User
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]); 
    }

    /**
     * @Route("/account/bookings", name="account_booking")
     * 
     * @return Response
     */
    public function bookings(){
        return $this->render("account/bookings.html.twig");
    }
}
