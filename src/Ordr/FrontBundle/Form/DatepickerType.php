<?php
namespace Ordr\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DatepickerType extends AbstractType
{
    public function getDefaultOptions(array $options)
    {
        return array(
            'widget' => 'single_text'
        );
    }

    public function getParent(array $options)
    {
        return 'date';
    }

    public function getName()
    {
        return 'datepicker';
    }
}