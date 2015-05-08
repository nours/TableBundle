<?php

namespace Nours\TableBundle\Extension;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ExtensionInterface
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface ExtensionInterface
{
    /**
     * Configures default options for a table.
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver);
}