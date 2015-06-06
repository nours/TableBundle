<?php

namespace Nours\TableBundle\Field\Type;

use Nours\TableBundle\Field\AbstractFieldType;
use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LabelType
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class LabelType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'label';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, array $options)
    {
        // todo : get this to work
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'labels' => array(
//                'text' => array(
//                    'style' => 'warning',
//                    'label' => 'Etat'
//                )
            )
        ));
    }
} 