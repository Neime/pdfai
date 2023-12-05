<?php

declare(strict_types=1);

namespace App;

final class ExtractedDatum
{
    public function __construct(
        private string $type,
        private string $name,
        private string $value,
    ) {
    }

    public function type(): string
    {
        return $this->type;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
