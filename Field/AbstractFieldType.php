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
use ReflectionClass;
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
    public function createField($name, array $options, array $ancestors): FieldInterface
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
    public function getParent(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        $reflection = new ReflectionClass($this);

        return Inflector::prefixFromClass($reflection->getShortName());
    }
}