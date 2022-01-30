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
use Nours\TableBundle\Field\Type\TextType;

/**
 * Class FQCNFieldType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class FQCNFieldType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }
}