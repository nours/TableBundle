<?php

namespace Nours\TableBundle\Extension;


use Nours\TableBundle\Table\TableInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\OptionsResolver\Options;
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
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'pager' => null,
            'page' => $this->makeCallback('page', 'getCurrentPage'),
            'limit' => $this->makeCallback('page', 'getMaxPerPage'),
        ));
        $resolver->setAllowedTypes(array(
            'pager' => array('Pagerfanta\Pagerfanta', 'null')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function loadTable(TableInterface $table, array $options)
    {
        /** @var Pagerfanta $pager */
        if ($pager = $options['pager']) {
            $results = $pager->getCurrentPageResults();

            // Results must be of type array for serialization
            if ($results instanceof \Traversable) {
                $results = iterator_to_array($results);
            }

            $table->setData($results);
            $table->setPages($pager->getNbPages());
            $table->setTotal($pager->getNbResults());
        }
    }

    /**
     * Makes a callback proxy to fantapagers.
     *
     * @param $option
     * @param $getter
     * @return callable
     */
    private function makeCallback($option, $getter)
    {
        return function(Options $options) use ($option, $getter) {
            if ($pager = $options['pager']) {
                return $pager->$getter();
            }
            return null;
        };
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