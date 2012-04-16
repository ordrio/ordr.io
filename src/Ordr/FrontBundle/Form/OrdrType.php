<?php

namespace Ordr\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrdrType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('amount')
            ->add('extra', 'text')
        ;
    }

    public function getName()
    {
        return 'ordr_databundle_ordrtype';
    }
}
