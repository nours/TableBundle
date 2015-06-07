<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Nours\TableBundle\Table\View;

/**
 * Class TableViewHandler
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableViewHandler implements SubscribingHandlerInterface
{

    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => 'Nours\TableBundle\Table\View',
                'format' => 'json',
                'method' => 'serializeTableViewToJson',
            )
        );
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param View $view
     * @param array $type
     * @param Context $context
     * @return array|\ArrayObject|mixed
     */
    public function serializeTableViewToJson(JsonSerializationVisitor $visitor, View $view, array $type, Context $context)
    {
        $data = array_intersect_key($view->vars, array_flip($view->serializedVars));

        return $visitor->visitArray($data, $type, $context);
    }
}