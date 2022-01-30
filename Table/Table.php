<?php

namespace Nours\TableBundle\Table;

use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;
use Nours\TableBundle\Field\FieldInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * A table instance.
 * 
 * @Serializer\ExclusionPolicy("all")
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class Table implements TableInterface
{
    /**
     * @var ResolvedType
     */
    private $type;

    /**
     * @var FieldInterface[]
     */
    private $fields;

    /**
     * @var array
     */
    private $options;

    /**
     * @var bool Flag
     */
    private $handled = false;

    /**
     * 
     * @param ResolvedType $type
     * @param FieldInterface[] $fields
     * @param array $options
     */
    public function __construct(ResolvedType $type, array $fields, array $options)
    {
        $this->type       = $type;
        $this->fields     = $fields;
        $this->options    = $options;

        foreach ($fields as $field) {
            $field->setTable($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ResolvedType
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->getOption('name');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function getField(string $name): FieldInterface
    {
        if (!isset($this->fields[$name])) {
            throw new InvalidArgumentException(sprintf(
                "Table type %s has no field named %s (but %s)",
                $this->getName(), $name, implode(', ', array_keys($this->fields))
            ));
        }

        return $this->fields[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getPage(): ?int
    {
        return $this->getOption('page');
    }

    /**
     * {@inheritDoc}
     */
    public function getLimit(): ?int
    {
        return $this->getOption('limit');
    }

    /**
     * {@inheritDoc}
     */
    public function getPages(): ?int
    {
        return $this->getOption('pages');
    }

    /**
     * {@inheritDoc}
     */
    public function getTotal(): ?int
    {
        return $this->getOption('total');
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $data = $this->getOption('data');

        /** @var callable $callback */
        $callback = $this->getOption('data_callback');

        if (empty($data) && $callback) {
            $data = $callback();
            $this->setOption('data', $data);
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function toJson(): array
    {
        $vars = $this->getOption('json_vars');

        if (is_callable($vars)) {
            $vars = $vars($this);
        }

        return array_merge(array(
            'page'  => $this->getPage(),
            'limit' => $this->getLimit(),
            'pages' => $this->getPages(),
            'total' => $this->getTotal(),
            'data'  => $this->getData(),
        ), $vars);
    }

    /**
     * {@inheritDoc}
     */
    public function hasData(): bool
    {
        return $this->getOption('data_callback') || $this->getOption('data');
    }

    /**
     * {@inheritDoc}
     */
    public function setPage(int $page)
    {
        $this->setOption('page', $page);
    }

    /**
     * {@inheritDoc}
     */
    public function setLimit(int $limit)
    {
        $this->setOption('limit', $limit);
    }

    /**
     * {@inheritDoc}
     */
    public function setPages(int $pages)
    {
        $this->setOption('pages', $pages);
    }

    /**
     * {@inheritDoc}
     */
    public function setTotal(int $total)
    {
        $this->setOption('total', $total);
    }

    /**
     * {@inheritDoc}
     */
    public function setData($data)
    {
        $this->setOption('data', $data);
    }

    /**
     * Sets the data lazy loader
     *
     * @param callable $callback
     */
    public function setDataCallback(callable $callback)
    {
        $this->setOption('data_callback', $callback);
    }

    /**
     * 
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->getOption('url');
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function createView(): View
    {
        // A table need to be handled before creating view
        return $this->handle()->getType()->createView($this);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request = null): TableInterface
    {
        if (false === $this->handled) {
            $this->type->handle($this, $request);
            $this->handled = true;
        }

        return $this;
    }
}