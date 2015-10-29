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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'strip_tags' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'text';
    }
}