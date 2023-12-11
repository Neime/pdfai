<?php

declare(strict_types=1);

namespace PDFAI\Tests\unit;

use PDFAI\PDFAI;
use PDFAI\Domain\UploadType;
use PHPUnit\Framework\TestCase;
use PDFAI\Domain\ExtractedDatum;
use PDFAI\Domain\UseCase\GetPdfContent;
use PDFAI\Extractor\ExtractorException;
use PDFAI\Extractor\ExtractorInterface;
use PDFAI\Domain\UseCase\ExtractDataFromPdf;

final class PDFAITest extends TestCase
{
    private PDFAI $pdfai;
    private ExtractorInterface $extractor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = $this->createStub(ExtractorInterface::class);
        $this->extractor->method('name')->willReturn('fake_extractor');

        $this->pdfai = new PDFAI($this->extractor);
    }

    public function test_it_throws_an_exception_if_extractor_not_found(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Extractor not found');

        $this->pdfai->extract(['name', 'firstname'], __DIR__.'/file.pdf', 'not_existing_extractor');
    }

    public function test_it_throws_an_exception_if_can_not_get_pdf_content(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(GetPdfContent::FILE_NOT_FOUND);

        $this->pdfai->extract(['name', 'firstname'], __DIR__.'/notfound.pdf', 'fake_extractor');
    }

    public function test_it_throws_an_exception_if_can_not_extract_data_from_pdf(): void
    {
        $this->extractor->method('extract')->willThrowException(new ExtractorException());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(ExtractDataFromPdf::PDF_EXTRACTOR_ERROR);

        $this->pdfai->extract(['name', 'firstname'], __DIR__.'/file.pdf', 'fake_extractor');
    }

    public function test_it_extract_data_from_pdf(): void
    {
        $this->extractor->method('extract')->willReturn([new ExtractedDatum('fake_extractor', 'name', 'Doe'), new ExtractedDatum('fake_extractor', 'firstname', 'Doe')]);

        $result = $this->pdfai->extract(
            data: ['name', 'firstname'],
            pdfPath: __DIR__.'/file.pdf',
        );

        $this->assertEquals(2, count($result));
        $this->assertEquals(
            ExtractedDatum::class,
            get_class($result[0])
        );

        $this->assertEquals(
            'fake_extractor',
            $result[0]->type()
        );
    }
}
