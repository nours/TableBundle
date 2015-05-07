<?php

namespace Nours\TableBundle\Extension;

use Nours\TableBundle\Table\Builder\TableBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractExtension
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class AbstractExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {

    }
} 