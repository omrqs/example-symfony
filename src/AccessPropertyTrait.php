<?php
namespace App;

trait AccessPropertyTrait
{
    public function __get(string $key): ?string
    {
        if (\property_exists($this, $key)) {
            return $this->{$key};
        }
    }

    public function __set(string $key, ?string $value): self
    {
        if (\property_exists($this, $key)) {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function __isset(string $key): bool
    {
        return \property_exists($this, $key);
    }
}
