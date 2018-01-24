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

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Field\AbstractFieldType;

class BooleanType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, FieldInterface $field, array $options)
    {
        $view->vars['true_text']  = $options['true_text'];
        $view->vars['false_text'] = $options['false_text'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'true_text'  => 'yes',
            'false_text' => 'no'
        ));
    }
}