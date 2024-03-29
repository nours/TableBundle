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

/**
 * Class NumberType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class NumberType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'number';
    }
}