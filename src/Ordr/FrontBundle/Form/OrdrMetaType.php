<?php

namespace Ordr\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class OrdrMetaType extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('public')
      ->add('next_ordr', 'datetime');
  }

  public function getName()
  {
    return 'ordr_frontbundle_ordrmetatype';
  }
}
