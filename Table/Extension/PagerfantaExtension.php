<?php

namespace Nours\TableBundle\Table\Extension;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'pager' => null,
            'page' =>  $this->makeCallback('getCurrentPage'),
            'limit' => $this->makeCallback('getMaxPerPage'),
            'pages' => $this->makeCallback('getNbPages'),
            'total' => $this->makeCallback('getNbResults'),
            'data' =>  $this->makeCallback('getCurrentPageResults'),
        ));
        $resolver->setAllowedTypes(array(
            'pager' => array('Pagerfanta\Pagerfanta', 'null')
        ));

        $resolver->setNormalizer('data', function(Options $options, $data) {
            return $data instanceof \Traversable ? iterator_to_array($data) : $data;
        });
    }

    /**
     * Makes a callback proxy to fantapagers.
     *
     * @param $getter
     * @return callable
     */
    private function makeCallback($getter)
    {
        return function(Options $options) use ($getter) {
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