<?php

declare(strict_types=1);

namespace Test\EndToEnd;

use App\ExtractedDatum;
use App\ExtractorInterface;

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