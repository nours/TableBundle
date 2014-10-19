<?php

namespace Nours\TableBundle\Extension;


use Nours\TableBundle\Table\AbstractExtension;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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