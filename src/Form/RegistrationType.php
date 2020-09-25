<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prenom", "Votre prénom ..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre Email ..."))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "URL de votre photo de profil (avatar) ..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un mot de passe solide (minimum 6 caractères"))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration("Confirmation", "Confirmez votre mot de passe ..."))        
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Présentez vous en quelques mots ..."))
            ->add('description', TextareaType ::class, $this->getConfiguration("Description", "Décrivez vous en détails ..."));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
