<?php

namespace Nours\TableBundle\Field;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface FieldTypeInterface
{
    /**
     * Creates a field for this type.
     * 
     * @param string $name
     * @param array $options
     */
    public function createField($name, array $options);
    
    /**
     * The name of this field type.
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Configures default options for this field.
     * 
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver);
}