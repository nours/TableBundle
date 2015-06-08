<?php

namespace Nours\TableBundle\Table\Extension;

use Nours\TableBundle\Table\TableInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Integrates a pagerfanta instance as a pager option.
 *
 * Changes the core default values to be loaded from the pagerfanta instance.
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PagerfantaExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'pager' => null
        ));
        $resolver->setAllowedTypes(array(
            'pager' => array('Pagerfanta\Pagerfanta', 'null')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {
        /** @var Pagerfanta $pager */
        if ($pager = $table->getOption('pager')) {
            $table->setPage($pager->getCurrentPage());
            $table->setLimit($pager->getMaxPerPage());
            $table->setPages($pager->getNbPages());
            $table->setTotal($pager->getNbResults());

            // Normalize data to array for serialization
            $data = $pager->getCurrentPageResults();
            $table->setData($data instanceof \Traversable ? iterator_to_array($data) : $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return 'core';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pagerfanta';
    }
} 