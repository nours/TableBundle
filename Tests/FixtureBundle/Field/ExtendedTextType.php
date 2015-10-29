<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\FixtureBundle\Field;

use Nours\TableBundle\Field\AbstractFieldType;

/**
 * Class ExtendedTextType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class ExtendedTextType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'extended_text';
    }
}