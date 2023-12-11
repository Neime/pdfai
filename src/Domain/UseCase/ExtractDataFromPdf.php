<?php

declare(strict_types=1);

namespace PDFAI\Domain\UseCase;

use PDFAI\Util\Result;
use PDFAI\Extractor\ExtractorException;
use PDFAI\Extractor\ExtractorInterface;

final class ExtractDataFromPdf
{
    public const PDF_EXTRACTOR_ERROR = 'pdf_extractor_error';
    public function __construct(
        private ExtractorInterface $extractor,
    ) {
    }

    /**
     * @return Result<array<ExtractedDatum>>
     */
    public function __invoke(array $data, string $pdfContent): Result
    {
        try {
            $extractedData = $this->extractor->extract(
                $data,
                $pdfContent,
            );
        } catch (ExtractorException $e) {
            return Result::failure(self::PDF_EXTRACTOR_ERROR, [
                'message' => $e->getMessage(),
            ]);
        }


        return Result::success($extractedData);
    }
}
