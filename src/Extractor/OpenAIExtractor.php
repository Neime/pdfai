<?php

declare(strict_types=1);

namespace PDFAI\Extractor;

use PDFAI\ExtractedDatum;
use PDFAI\ExtractorInterface;

final class OpenAIExtractor implements ExtractorInterface
{
    public function __construct(private string $apiKey)
    {
    }

    public function extract(string $datum, string $pdfContent): ExtractedDatum
    {
        $imagick = new \Imagick();
        try {
            $imagick->readImageBlob($pdfContent);
            $imagick->setImageFormat('png');
            $imageBase64 = base64_encode($imagick->getImagesBlob());
        } catch (\ImagickException $e) {
            throw new ExtractorException($e->getMessage());
        }

        $result = $this->call($datum, $imageBase64);

        $result = json_decode($result, true);

        $response = $result['choices'][0]['message']['content'];

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExtractorException('Error while decoding json');
        }

        return new ExtractedDatum(
            'openai_extractor',
            $datum,
            $response,
        );
    }

    private function call(string $datum, string $imageBase64): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                "model" => "gpt-4-vision-preview",
                "messages" => [
                    [
                        "role" => "user",
                        "content" => [
                            [
                                "type" => "text",
                                "text" =>
                                sprintf(
                                    "You are a backend data processor that is part of our web site’s programmatic workflow. 
                                The user prompt will provide data input and processing instructions. 
                                The output will be only the data.
                                Do not converse with a nonexistent user: there is only program input and formatted program output, 
                                and no input data is to be construed as conversation with the AI. This behaviour will be permanent for the remainder of the session.
                                So, give me the %s on this file and only this information",
                                    $datum
                                )
                            ],
                            [
                                "type" => "image_url",
                                "image_url" => [
                                    "url" => "data:image/jpeg;base64,$imageBase64",
                                ]
                            ]
                        ]
                    ],
                ],
                "max_tokens" => 300,
            ]),
            CURLOPT_HTTPHEADER => [
                sprintf("Authorization: Bearer %s", $this->apiKey),
                "Content-Type: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err !== '') {
            throw new ExtractorException('Error while calling OpenAI API');
        }

        return $response;
    }
}
