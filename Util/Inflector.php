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

/**
 * Class Inflector
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class Inflector
{
    public static function prefixFromClass($className)
    {
        // Remove 'Type' from the end of class name
        $pos = strrpos($className, 'Type');
        if ($pos == strlen($className) - 4) {
            $className = substr($className, 0, -4);
        }

        return \Doctrine\Common\Inflector\Inflector::tableize($className);
    }
}