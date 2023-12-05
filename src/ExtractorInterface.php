<?php

declare(strict_types=1);

namespace App;

interface ExtractorInterface
{
    public function extract(string $datum, string $pdfContent): ExtractedDatum;
}
