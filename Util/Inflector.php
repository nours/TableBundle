<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Util;

use Doctrine\Inflector\InflectorFactory;

/**
 * Class Inflector
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
final class Inflector
{
    private static function getInflector()
    {
        static $inflector;

        if (!$inflector) {
            $inflector = InflectorFactory::create()->build();
        }

        return $inflector;
    }

    public static function classify($word)
    {
        return self::getInflector()->classify($word);
    }

    public static function tableize($word)
    {
        return self::getInflector()->tableize($word);
    }

    public static function prefixFromClass($className)
    {
        // Remove 'Type' from the end of class name
        $pos = strrpos($className, 'Type');
        if ($pos == strlen($className) - 4) {
            $className = substr($className, 0, -4);
        }

        return self::tableize($className);
    }
}