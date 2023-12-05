<?php

declare(strict_types=1);

namespace PDFAI;

interface ExtractorInterface
{
    public function extract(string $datum, string $pdfContent): ExtractedDatum;
}
