<?php

namespace Nours\TableBundle\Field\Type;

use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Field\AbstractFieldType;

class BooleanType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, array $options)
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