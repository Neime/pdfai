<?php

declare(strict_types=1);

namespace App;

final class ExtractDataFromPdf
{
    public function __construct(
        private ExtractorInterface $extractor,
    ) {
    }

    public function __invoke(array $data, string $pdfContent): Result
    {
        $extractedData = array_map(
            fn (string $datum) => $this->extractor->extract(
                $datum,
                $pdfContent,
            ),
            $data
        );

        return Result::success($extractedData);
    }
}
