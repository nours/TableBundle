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
 * Class ExtendedTextType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class ExtendedTextType extends AbstractFieldType
{
    /**
     * DI check
     *
     * @param array $arg
     */
    public function __construct(array $arg)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'extended_text';
    }
}