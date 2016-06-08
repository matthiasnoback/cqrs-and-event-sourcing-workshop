<?php

namespace EventSourcing\Aggregate;

trait EventCapabilities
{
    private $metadata = [];

    final public function withMetadata(string $key, $value)
    {
        $copy = clone $this;
        $copy->metadata[$key] = $value;

        return $copy;
    }

    final public function metadata(string $key)
    {
        if (!array_key_exists($key, $this->metadata)) {
            throw new \InvalidArgumentException(
                sprintf('Undefined metadata with key "%s"', $key)
            );
        }

        return $this->metadata[$key];
    }
}
