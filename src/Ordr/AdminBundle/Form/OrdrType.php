<?php

namespace Ordr\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrdrType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('amount')
            ->add('extra')
            ->add('public')
            ->add('created_at')
            ->add('session')
            ->add('ordr')
        ;
    }

    public function getName()
    {
        return 'ordr_adminbundle_ordrtype';
    }
}
