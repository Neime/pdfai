<?php

declare(strict_types=1);

namespace PDFAI\Domain;

final class ExtractedDatum
{
    public function __construct(
        private string $type,
        private string $search,
        private string $extractedValue,
    ) {
    }

    public function type(): string
    {
        return $this->type;
    }

    public function search(): string
    {
        return $this->search;
    }

    public function extractedValue(): string
    {
        return $this->extractedValue;
    }

    /**
     * @return array<string, string>
     */
    public function print(): array
    {
        return [
            'type' => $this->type,
            'search' => $this->search,
            'extracted_value' => $this->extractedValue,
        ];
    }
}
