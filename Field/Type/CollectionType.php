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
 * Class CollectionType
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class CollectionType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, FieldInterface $field, array $options)
    {
        $view->vars['separator']  = $options['separator'];
        $view->vars['text_path']  = $options['text_path'];
        $view->vars['empty_text'] = $options['empty_text'];
        $view->vars['truncate']   = $options['truncate'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // property_path is relative to the objects in collection field not the main data
        $resolver->setDefaults(array(
            'text_path'  => null,
            'separator'  => ', ',
            'empty_text' => null,
            'truncate'   => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'collection';
    }
}