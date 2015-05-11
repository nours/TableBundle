<?php

namespace Nours\TableBundle\Extension;


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
            'pages' => $this->makeCallback('page', 'getNbPages'),
            'total' => $this->makeCallback('page', 'getNbResults'),
            'data' => $this->makeCallback('data', 'getCurrentPageResults'),
        ));
        $resolver->setAllowedTypes(array(
            'pager' => array('Pagerfanta\Pagerfanta', 'null')
        ));
    }

    private function makeCallback($option, $getter)
    {
        return function(Options $options) use ($option, $getter) {
            if ($pager = $options['pager']) {
                return $pager->$getter();
            }
        };
    }
} 