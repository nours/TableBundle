<?php

namespace Nours\TableBundle\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Field\AbstractFieldType;

class BooleanType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean';
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'true_icon'  => 'check',
            'false_icon' => 'times'
        ));
    }
}