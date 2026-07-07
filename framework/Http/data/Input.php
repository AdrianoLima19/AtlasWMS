<?php

declare(strict_types=1);

namespace Weave\Http\Data;

class Input extends Parameter
{
    public function getInt(string $key, int $default = 0): int
    {
        return (int) $this->get($key, $default);
    }

    public function getFloat(string $key, float $default = 0.0): float
    {
        return (float) $this->get($key, $default);
    }

    public function getBool(string $key, bool $default = false): bool
    {
        $value = $this->get($key, $default);

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function getString(string $key, string $default = ''): string
    {
        return (string) $this->get($key, $default);
    }

    public function getArray(string $key, array $default = []): array
    {
        $value = $this->get($key, $default);

        return \is_array($value) ? $value : $default;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @param int $filter
     * @param mixed $options
     *
     * @return mixed
     */
    public function filter(string $key, mixed $default = null, int $filter = \FILTER_DEFAULT, mixed $options = []): mixed
    {
        $value = $this->get($key, $default);

        if (!\is_array($options) && $options) {
            $options = ['flags' => $options];
        }

        if (\is_array($value) && !(($options['flags'] ?? 0) & \FILTER_REQUIRE_ARRAY)) {
            $options['flags'] = ($options['flags'] ?? 0) | \FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }
}
