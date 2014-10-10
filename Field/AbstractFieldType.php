<?php

namespace Nours\TableBundle\Field;

use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 *
 */
abstract class AbstractFieldType implements FieldTypeInterface
{
    /**
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\Field\FieldTypeInterface::createField()
     */
    public function createField($name, array $options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array(
            'label'    => null,
            'sortable' => false,
            'width' => null
        ));

        $this->setDefaultOptions($resolver);
        
        $options = $resolver->resolve($options);
        
        return new Field($name, $this->getName(), $options);
    }
}