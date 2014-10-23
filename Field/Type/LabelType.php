<?php

namespace Nours\TableBundle\Field\Type;

use Nours\TableBundle\Field\AbstractFieldType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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