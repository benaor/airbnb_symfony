<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{

    /**
     * For configure base of a input
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder)
    {
        return [
            "label" => $label,
            "attr" => [
                "placeholder" => $placeholder
            ]
        ];
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("titre" , "Indiquez le titre de l'annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web" , "entrez l'adresse web (automatique)"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principal" , "Indiqué l'URL de votre image principale"))
            ->add('introduction', TextType::class, $this->getConfiguration("Intruduction" , "decrivez globalement votre annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("description" , "faites une description détaillé de votre logement"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambre" , "nombre de chambre(s) disponible(s)"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit" , "Indiquez le prix d'une nuit dans ce logement"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
