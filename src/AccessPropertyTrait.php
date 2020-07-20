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

        return null;
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
}
