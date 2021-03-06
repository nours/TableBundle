<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Field;

use Nours\TableBundle\Table\View;
use Nours\TableBundle\Util\Inflector;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 *
 */
abstract class AbstractFieldType implements FieldTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function createField($name, array $options, array $ancestors)
    {
        return new Field($name, $this, $options, $ancestors);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, FieldInterface $field, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        // Backward compatibility
        if ($name = $this->getName()) {
            trigger_error(sprintf(
                'Implementing getName function on field type %s is deprecated. Rename it to getBlockPrefix.',
                get_class($this)
            ), E_USER_DEPRECATED);

            return $name;
        }

        $reflection = new \ReflectionClass($this);

        return Inflector::prefixFromClass($reflection->getShortName());
    }
}