<?php

namespace Nours\TableBundle\Field;

use Nours\TableBundle\Table\View;
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
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Builds a view for this field type.
     *
     * @param View $view
     * @param array $options
     * @return mixed
     */
    public function buildView(View $view, array $options);
}