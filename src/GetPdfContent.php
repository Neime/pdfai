<?php

declare(strict_types=1);

namespace PDFAI;

final class GetPdfContent
{
    public const FILE_NOT_FOUND = 'file_not_found';
    public const FILE_EMPTY = 'file_empty';
    public const FILE_NOT_PDF = 'file_not_pdf';

    public function __invoke(UploadType $type, string $path): Result
    {
        $hasFile = match ($type) {
            UploadType::LOCAL => $this->existLocal($path),
            UploadType::URL => $this->existUrl($path),
        };

        if (!$hasFile) {
            return Result::failure(
                self::FILE_NOT_FOUND,
                ['path' => $path]
            );
        }

        $content = file_get_contents($path);

        if (empty($content)) {
            return Result::failure(
                self::FILE_EMPTY,
                ['path' => $path]
            );
        }

        if (!$this->isPdf($content)) {
            return Result::failure(
                self::FILE_NOT_PDF,
                ['path' => $path]
            );
        }

        return Result::success($content);
    }

    private function existLocal(string $path): bool
    {
        return file_exists($path);
    }

    private function existUrl(string $url): bool
    {
        $headers = get_headers($url);

        return !empty($headers) && strpos($headers[0], '200');
    }

    private function isPdf(string $content): bool
    {
        return substr($content, 0, 4) === '%PDF';
    }
}
