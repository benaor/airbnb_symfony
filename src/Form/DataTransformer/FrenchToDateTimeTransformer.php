<?php

namespace App\Form\DataTransformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface {

    public function transform($date)
    {
        if ($date === null) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        if($frenchDate === null){
            throw new TransformationFailedException("Vous devez entrer une date");
        }

        $date = \DateTime::CreateFromFormat('d/m/Y', $frenchDate);

        if ($frenchDate === false) {
            throw new TransformationFailedException("Vous devez entrer une date au format : JJ/MM/AAAA");
        }

        return $date;
    }

}