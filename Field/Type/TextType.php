<?php

namespace Nours\TableBundle\Field\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Nours\TableBundle\Field\AbstractFieldType;

class TextType extends AbstractFieldType
{
    /**
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\Field\FieldTypeInterface::getName()
     */
    public function getName()
    {
        return 'text';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\Field\FieldTypeInterface::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }
}