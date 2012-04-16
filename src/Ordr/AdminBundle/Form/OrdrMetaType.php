<?php

namespace Ordr\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrdrMetaType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('next_ordr')
            ->add('token')
            ->add('adminToken')
        ;
    }

    public function getName()
    {
        return 'ordr_adminbundle_ordrmetatype';
    }
}
