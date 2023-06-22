<?php

namespace App\Util\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormErrorIterator;

class FormErrors
{
    public static function getErrors(FormInterface $form): array
    {
        $result = [];

        foreach ($form->getErrors(true, false) as $formError) {
            if ($formError instanceof FormError) {
                $result[$formError->getOrigin()->getName()] = $formError->getMessage();
            } elseif ($formError instanceof FormErrorIterator) {
                $result = array_merge($result, self::getErrors($formError->getForm()));
            }
        }

        return $result;
    }
}
