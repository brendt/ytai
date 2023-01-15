<?php

namespace App\Data;

final readonly class Dimensions
{
    public function __construct(
        public int $width,
        public int $height,
    ) {}

    public function __toString(): string
    {
        return "{$this->width}x{$this->height}";
    }
}
