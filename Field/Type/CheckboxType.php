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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Field\AbstractFieldType;

class CheckboxType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'checkbox';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('configs', array(
            'checkbox' => true
        ));
    }
}