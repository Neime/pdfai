<?php

declare(strict_types=1);

namespace PDFAI\Tests\Unit\Domain\UseCase;

use PDFAI\Domain\UploadType;
use PHPUnit\Framework\TestCase;
use PDFAI\Domain\ExtractedDatum;
use PDFAI\Domain\UseCase\GetPdfContent;
use PDFAI\Extractor\ExtractorInterface;
use PDFAI\Domain\UseCase\ExtractDataFromPdf;

final class ExtractDataFromPdfTest extends TestCase
{
    private ExtractDataFromPdf $useCase;
    private ExtractorInterface $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = $this->createStub(ExtractorInterface::class);
        $this->useCase = new ExtractDataFromPdf($this->extractor);
    }

    public function test_it_extract_data_from_pdf(): void
    {
        $this->extractor->method('extract')->willReturn([new ExtractedDatum('my_extractor', 'name', 'Doe')]);

        $result = ($this->useCase)(['name'], (new GetPdfContent())(UploadType::LOCAL, __DIR__.'/../../file.pdf')->getData());
        
        $this->assertTrue($result->isSuccess());
        $this->assertEquals(1, count($result->getData()));
        $this->assertEquals(
            ExtractedDatum::class,
            get_class($result->getData()[0])
        );

        $this->assertEquals(
            'my_extractor',
            $result->getData()[0]->type()
        );
    }
}
