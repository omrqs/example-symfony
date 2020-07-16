<?php
namespace App;

trait AccessPropertyTrait
{
    /**
     * Getter magic.
     */
    public function __get(string $key): ?string
    {
        if (\property_exists($this, $key)) {
            return $this->{$key};
        }
    }

    /**
     * Setter magic.
     */
    public function __set(string $key, ?string $value): self
    {
        if (\property_exists($this, $key)) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Isset magic.
     */
    public function __isset(string $key): bool
    {
        return \property_exists($this, $key);
    }
}
