<?php

namespace Nours\TableBundle\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Field\AbstractFieldType;

class DateType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date';
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'format' => 'Y-m-d'
        ));
    }
}