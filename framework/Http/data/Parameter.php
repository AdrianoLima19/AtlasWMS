<?php

declare(strict_types=1);

namespace Weave\Http\Data;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Parameter implements ArrayAccess, Countable, IteratorAggregate
{
    public function __construct(protected array $items = []) {}

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return $this->items === [];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return Parameter
     */
    public function set(string $key, mixed $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return Parameter
     */
    public function add(array $items): self
    {
        return $this->merge($items);
    }

    /**
     * @param string $key
     *
     * @return Parameter
     */
    public function remove(string $key): self
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * @param array $items
     *
     * @return Parameter
     */
    public function replace(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return Parameter
     */
    public function merge(array $items): self
    {
        $this->items = [...$this->items, ...$items];

        return $this;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function only(array $keys): array
    {
        return \array_intersect_key($this->items, array_flip($keys));
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function except(array $keys): array
    {
        return \array_diff_key($this->items, array_flip($keys));
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has((string) $offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get((string) $offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;

            return;
        }

        $this->set((string) $offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove((string) $offset);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->get($name, null);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function __unset(string $name): void
    {
        $this->remove($name);
    }
}
