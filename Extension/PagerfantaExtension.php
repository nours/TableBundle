<?php

namespace Nours\TableBundle\Extension;


use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Enables the use of Pagerfanta pagers.
 *
 * Normalizes options from a pager instance.
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
            'page' => 1,
            'limit' => 10,
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