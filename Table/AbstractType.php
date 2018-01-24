<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Util\Inflector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Builder\TableBuilder;

/**
 * Abstract type for tables.
 * 
 * Inherit from this base class in order to build new tables.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
abstract class AbstractType implements TableTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        // Backward compatibility
        if ($name = $this->getName()) {
            trigger_error(sprintf(
                'Implementing getName function on table type %s is deprecated. Rename it to getBlockPrefix.',
                get_class($this)
            ), E_USER_DEPRECATED);

            return $name;
        }

        $reflection = new \ReflectionClass($this);

        return Inflector::prefixFromClass($reflection->getShortName());
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {

    }
}