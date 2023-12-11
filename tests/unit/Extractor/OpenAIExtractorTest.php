<?php

declare(strict_types=1);

namespace PDFAI\Tests\Unit;

use Dotenv\Dotenv;
use PDFAI\Domain\UploadType;
use PHPUnit\Framework\TestCase;
use PDFAI\Extractor\OpenAIExtractor;
use PDFAI\Domain\UseCase\GetPdfContent;

final class OpenAIExtractorTest extends TestCase
{
    private OpenAIExtractor $openAIExtractor;

    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createImmutable('./');
        $dotenv->load();

        $this->openAIExtractor = new OpenAIExtractor($_ENV['OPENAI_API_KEY']);
    }

    public function test_it_extract_data_from_pdf(): void
    {
        $extractedData = $this->openAIExtractor->extract(['first name', 'last name'], (new GetPdfContent())(UploadType::LOCAL, __DIR__.'/../file.pdf')->getData());
        
        $this->assertEquals(2, count($extractedData));
        $this->assertEquals(
            'John',
            $extractedData[0]->extractedValue()
        );

        $this->assertEquals(
            'Doe',
            $extractedData[1]->extractedValue()
        );
    }

    public function test_it_extract_data_from_pdf_with_url(): void
    {
        $extractedData = $this->openAIExtractor->extract(['country', 'state / region'], (new GetPdfContent())(UploadType::URL, 'https://www.orimi.com/pdf-test.pdf')->getData());
        
        $this->assertEquals(2, count($extractedData));
        $this->assertEquals(
            'Canada',
            $extractedData[0]->extractedValue()
        );

        $this->assertEquals(
            'Yukon',
            $extractedData[1]->extractedValue()
        );
    }
}
