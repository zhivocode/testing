<?php

declare(strict_types=1);

namespace Zhivocode\Testing;

final class Data implements IData
{
    private array $values = [];

    public function __construct(private readonly ?IData $parent = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value): Data
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unset(string $key): IData
    {
        $this->parent?->unset($key);

        unset($this->values[$key]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isset(string $key): bool
    {
        return $this->parent?->isset($key) || isset($this->values[$key]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        return $this->values[$key] ?? $this->parent?->get($key);
    }
}
