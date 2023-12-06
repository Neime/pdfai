<?php

declare(strict_types=1);

namespace PDFAI;

use PDFAI\Extractor\ExtractorException;

final class ExtractDataFromPdf
{
    public const PDF_EXTRACTOR_ERROR = 'pdf_extractor_error';
    public function __construct(
        private ExtractorInterface $extractor,
    ) {
    }

    public function __invoke(array $data, string $pdfContent): Result
    {
        try {
            $extractedData = array_map(
                fn (string $datum) => $this->extractor->extract(
                    $datum,
                    $pdfContent,
                ),
                $data
            );
        } catch (ExtractorException $e) {
            return Result::failure(self::PDF_EXTRACTOR_ERROR, [
                'message' => $e->getMessage(),
            ]);
        }


        return Result::success($extractedData);
    }
}
