<?php

namespace Nours\TableBundle\Field\Type;

use Nours\TableBundle\Field\AbstractFieldType;

/**
 * Class CollectionType
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class CollectionType extends AbstractFieldType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'collection';
    }
}