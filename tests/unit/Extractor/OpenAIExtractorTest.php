<?php

declare(strict_types=1);

namespace PDFAI\Tests\Unit;

use PDFAI\UploadType;
use PDFAI\GetPdfContent;
use PHPUnit\Framework\TestCase;
use PDFAI\Extractor\OpenAIExtractor;
use Dotenv\Dotenv;

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
        $extractedDatum = $this->openAIExtractor->extract('first name', (new GetPdfContent())(UploadType::LOCAL, __DIR__.'/../file.pdf')->getData());
        
        $this->assertEquals(
            'John',
            $extractedDatum->value()
        );
    }
}
