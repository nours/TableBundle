<?php

namespace Nours\TableBundle\Extension;

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
    public function setDefaultOptions(OptionsResolver $resolver)
    {

    }
} 