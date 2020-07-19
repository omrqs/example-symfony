<?php
namespace App;

trait AccessPropertyTrait
{
    /**
     * Getter magic.
     */
    public function __get(string $key): ?string
    {
        return $this->{$key};
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
