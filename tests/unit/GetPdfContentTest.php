<?php

declare(strict_types=1);

namespace Test;

use App\GetPdfContent;
use App\UploadType;
use PHPUnit\Framework\TestCase;

final class GetPdfContentTest extends TestCase
{
    private GetPdfContent $useCase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = new GetPdfContent();
    }

    public function test_it_founds_local_file(): void
    {
        $result = ($this->useCase)(UploadType::LOCAL, __DIR__.'/file.pdf');

        $this->assertTrue($result->isSuccess());
    }

    public function test_it_founds_url(): void
    {
        $result = ($this->useCase)(UploadType::URL, 'https://www.orimi.com/pdf-test.pdf');

        $this->assertTrue($result->isSuccess());
    }

    public function test_it_cant_found_local_file(): void
    {
        $result = ($this->useCase)(UploadType::LOCAL, __DIR__.'/notfound.pdf');

        $this->assertTrue($result->isFailure());
        $this->assertEquals(
            GetPdfContent::FILE_NOT_FOUND,
            $result->getErrorCode()
        );
        $this->assertEquals(
            ['path' => __DIR__.'/notfound.pdf'],
            $result->getErrorDetails()
        );
    }

    public function test_it_cant_read_local_file(): void
    {
        $result = ($this->useCase)(UploadType::LOCAL, __DIR__.'/empty.pdf');

        $this->assertTrue($result->isFailure());
        $this->assertEquals(
            GetPdfContent::FILE_EMPTY,
            $result->getErrorCode()
        );
        $this->assertEquals(
            ['path' => __DIR__.'/empty.pdf'],
            $result->getErrorDetails()
        );
    }

    public function test_it_not_pdf(): void
    {
        $result = ($this->useCase)(UploadType::LOCAL, __DIR__.'/file.txt');

        $this->assertTrue($result->isFailure());
        $this->assertEquals(
            GetPdfContent::FILE_NOT_PDF,
            $result->getErrorCode()
        );
        $this->assertEquals(
            ['url' => __DIR__.'/file.txt'],
            $result->getErrorDetails()
        );
    }
}
