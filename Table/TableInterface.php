<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;
use Symfony\Component\HttpFoundation\Request;

interface TableInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ResolvedType
     */
    public function getType(): ResolvedType;

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array;

    /**
     * @param string $name
     * @return FieldInterface
     */
    public function getField(string $name): FieldInterface;

    /**
     * @return integer
     */
    public function getPage(): ?int;

    /**
     * @return integer
     */
    public function getLimit(): ?int;

    /**
     * @return integer
     */
    public function getPages(): ?int;

    /**
     * @return integer
     */
    public function getTotal(): ?int;

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return bool
     */
    public function hasData(): bool;

    /**
     * @param integer $page
     */
    public function setPage(int $page);

    /**
     * @param integer $limit
     */
    public function setLimit(int $limit);

    /**
     * @param integer $pages
     */
    public function setPages(int $pages);

    /**
     * @param integer $total
     */
    public function setTotal(int $total);

    /**
     * @param mixed $data
     */
    public function setData($data);

    /**
     * Sets data lazy loader
     *
     * @param callable $callback
     */
    public function setDataCallback(callable $callback);

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @param $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setOption(string $name, $value);

    /**
     * @return View
     */
    public function createView(): View;

    /**
     * @param Request|null $request
     *
     * @return TableInterface
     */
    public function handle(Request $request = null): TableInterface;

    /**
     * @return array
     */
    public function toJson(): array;
}