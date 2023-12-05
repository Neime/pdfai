<?php

declare(strict_types=1);

namespace App;

/** @template T **/
final readonly class Result
{
    /**
     * @param array<string, mixed> $errorDetails
     */
    private function __construct(
        private ResultType $type,
        private mixed $data = null,
        private ?string $failureCode = null,
        private ?array $failureDetails = null,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->type === ResultType::SUCCESS;
    }

    public function isFailure(): bool
    {
        return $this->type === ResultType::FAILURE;
    }

    public static function success(mixed $data = null): self
    {
        return new self(ResultType::SUCCESS, $data);
    }

    /**
     * @param array<string, mixed> $details
     */
    public static function failure(string $code, array $details = null): self
    {
        return new self(ResultType::FAILURE, null, $code, $details);
    }

    public function getErrorCode(): ?string
    {
        if ($this->isSuccess()) {
            throw new \Exception('Result is not a failure');
        }

        return $this->failureCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrorDetails(): ?array
    {
        if ($this->isSuccess()) {
            throw new \Exception('Result is not a failure');
        }

        return $this->failureDetails;
    }

    public function getData(): mixed
    {
        if ($this->isFailure()) {
            throw new \Exception('Result is not a success');
        }

        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function print(): array
    {
        $print = [
            'type' => $this->type->value,
        ];

        if ($this->isSuccess()) {
            return array_merge($print, [
                'data' => $this->data,
            ]);
        }

        return array_merge($print, [
            'code' => $this->failureCode,
            'details' => $this->failureDetails,
        ]);
    }
}
