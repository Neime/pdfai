<?php

header("Access-Control-Allow-Origin: *");

include __DIR__.'/../../vendor/autoload.php';
use PDFAI\GetPdfContent;
use PDFAI\ExtractDataFromPdf;
use PDFAI\Tests\EndToEnd\FakeExtractor;
use PDFAI\UploadType;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was selected for upload
    if (isset($_FILES['file'])) {
        $result = (new GetPdfContent())(UploadType::LOCAL, $_FILES['file']['tmp_name']);

        $print = $result->print();

        if ($result->isSuccess()) {
            $print['data'] = null;
        }

        $result = (new ExtractDataFromPdf(new FakeExtractor()))(['name', 'firstname'], $result->getData());

        if ($result->isSuccess()) {
            $print['data'] = null;
        }

        foreach ($result->getData() as $extractedDatum) {
            $print['data'][] = $extractedDatum->print();
        }

        
        echo json_encode($print);
    } else {
        echo 'No file selected for upload.';
    }
}
