<?php

declare(strict_types=1);

namespace PDFAI\Tests\Unit;

use PDFAI\UploadType;
use PDFAI\GetPdfContent;
use PDFAI\ExtractDataFromPdf;
use PDFAI\ExtractedDatum;
use PDFAI\ExtractorInterface;
use PHPUnit\Framework\TestCase;

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
        $this->extractor->method('extract')->willReturn(new ExtractedDatum('my_extractor', 'name', 'Doe'));

        $result = ($this->useCase)(['name', 'firstname'], (new GetPdfContent())(UploadType::LOCAL, __DIR__.'/file.pdf')->getData());

        $this->assertTrue($result->isSuccess());
        $this->assertEquals(2, count($result->getData()));
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
