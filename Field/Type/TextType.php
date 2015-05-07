<?php

namespace Nours\TableBundle\Field\Type;

use Nours\TableBundle\Field\AbstractFieldType;

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
    public function getName()
    {
        return 'text';
    }
}