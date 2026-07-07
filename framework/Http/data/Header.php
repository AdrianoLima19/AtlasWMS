<?php

declare(strict_types=1);

namespace Weave\Http\Data;

class Header extends Parameter
{
    /**
     * @inheritdoc
     */
    public function __construct(array $headers = [])
    {
        $normalized = [];

        foreach ($headers as $name => $value) {
            $normalized[$this->normalize((string) $name)] = $value;
        }

        parent::__construct($normalized);
    }

    /**
     * @param array $server
     *
     * @return Header
     */
    public static function fromServer(array $server): self
    {
        $headers = [];

        foreach ($server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                $headers[$key] = $value;
            }
        }

        return new self($headers);
    }

    /**
     * @inheritdoc
     */
    public function has(string $key): bool
    {
        return \array_key_exists($this->normalize($key), $this->items);
    }

    /**
     * @inheritdoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$this->normalize($key)] ?? $default;
    }

    /**
     * @inheritdoc
     */
    public function set(string $key, mixed $value): static
    {
        $this->items[$this->normalize($key)] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function add(array $items): self
    {
        foreach ($items as $name => $value) {
            $this->items[$this->normalize((string) $name)] = $value;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(string $key): self
    {
        unset($this->items[$this->normalize($key)]);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function replace(array $items): self
    {
        $this->items = [];

        return $this->add($items);
    }

    /**
     * @inheritdoc
     */
    public function merge(array $items): self
    {
        $normalized = [];

        foreach ($items as $name => $value) {
            $normalized[$this->normalize((string) $name)] = $value;
        }

        $this->items = [...$this->items, ...$normalized];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function only(array $keys): array
    {
        $normalized = [];

        foreach ($keys as $name => $value) {
            $normalized[$this->normalize((string) $name)] = $value;
        }

        return \array_intersect_key($this->items, array_flip($normalized));
    }

    /**
     * @inheritdoc
     */
    public function except(array $keys): array
    {
        $normalized = [];

        foreach ($keys as $name => $value) {
            $normalized[$this->normalize((string) $name)] = $value;
        }

        return \array_diff_key($this->items, array_flip($normalized));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function normalize(string $name): string
    {
        return str_replace('_', '-', strtolower($name));
    }
}
