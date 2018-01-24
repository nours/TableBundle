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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TextType
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TextType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, FieldInterface $field, array $options)
    {
        $view->vars['strip_tags']  = $options['strip_tags'];
        $view->vars['truncate']    = $options['truncate'];
        $view->vars['empty_value'] = $options['empty_value'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'strip_tags' => function(Options $options) {
                return $options['truncate'] ? true : false;
            },
            'truncate' => null,
            'empty_value' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'text';
    }
}