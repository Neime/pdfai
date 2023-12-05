<?php

declare(strict_types=1);

namespace PDFAI\Tests\EndToEnd;

use PDFAI\ExtractedDatum;
use PDFAI\ExtractorInterface;

class FakeExtractor implements ExtractorInterface
{
    
    public function extract(string $datum, string $pdfContent): ExtractedDatum
    {
        return new ExtractedDatum(
            'fake_extractor',
            $datum,
            'Doe',
        );
    }
}
