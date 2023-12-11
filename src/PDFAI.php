<?php

declare(strict_types=1);

namespace PDFAI;

use PDFAI\Domain\ExtractedDatum;
use PDFAI\Domain\UploadType;
use PDFAI\Domain\UseCase\ExtractDataFromPdf;
use PDFAI\Domain\UseCase\GetPdfContent;
use PDFAI\Extractor\OpenAIExtractor;
use PDFAI\Extractor\ExtractorInterface;

final class PDFAI
{
    /**
     * @var array<string, ExtractorInterface>
     */
    private array $extractors;

    public function __construct(
        ExtractorInterface ...$extractors
    ) {
        foreach ($extractors as $extractor) {
            $this->extractors[$extractor->name()] = $extractor;
        }
    }

    /**
     * @param array<string, string> $data
     * @return array<ExtractedDatum>
     */
    public function extract(
        array $data,
        string $pdfPath,
        string $extractorName = null,
        UploadType $uploadType = UploadType::LOCAL
    ): array {
        $extractor = $this->getExtractor($extractorName);

        $getPdfContent = (new GetPdfContent($extractor))($uploadType, $pdfPath);
        if ($getPdfContent->isFailure()) {
            throw new \Exception($getPdfContent->getErrorCode());
        }
        $content = $getPdfContent->getData();

        $extractDataFromPdf = (new ExtractDataFromPdf($extractor))($data, $content);
        if ($extractDataFromPdf->isFailure()) {
            throw new \Exception($extractDataFromPdf->getErrorCode());
        }

        return $extractDataFromPdf->getData();
    }

    private function getExtractor(?string $extractorName): ExtractorInterface
    {
        if ($extractorName === null) {
            return array_shift($this->extractors);
        }

        $extractor = $this->extractors[$extractorName] ?? null;

        if ($extractor === null) {
            throw new \Exception('Extractor not found');
        }

        return $extractor;
    }
}
