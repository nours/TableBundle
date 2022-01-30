<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Util\Inflector;
use ReflectionClass;
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
    public function getBlockPrefix(): string
    {
        $reflection = new ReflectionClass($this);

        return Inflector::prefixFromClass($reflection->getShortName());
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension(): ?string
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