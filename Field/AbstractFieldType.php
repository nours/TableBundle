<?php

namespace Nours\TableBundle\Field;

use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 *
 */
abstract class AbstractFieldType implements FieldTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function createField($name, array $options)
    {
        return new Field($name, $this, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}