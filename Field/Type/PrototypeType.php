<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Field\Type;

use Nours\TableBundle\Field\AbstractFieldType;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrototypeType
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PrototypeType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, FieldInterface $field, array $options)
    {
        $view->vars['prototype'] = $options['prototype'];
        $view->vars['mappings']  = $options['mappings'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'prototype' => null,
            'mappings'  => array()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'prototype';
    }
}