<?php

namespace Nours\TableBundle\Extension;

use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\TableInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractExtension
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
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
    public function finishTable(TableBuilder $builder, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function loadTable(TableInterface $table, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return null;
    }
} 