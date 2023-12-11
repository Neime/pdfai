<?php

declare(strict_types=1);

namespace PDFAI\Extractor;

use PDFAI\Domain\ExtractedDatum;

interface ExtractorInterface
{
    public function name(): string;

    /**
     * @param array<string, string> $data
     * @return array<ExtractedDatum>
     */
    public function extract(array $data, string $pdfContent): array;
}
