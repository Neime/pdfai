<?php

header("Access-Control-Allow-Origin: *");

include __DIR__.'/../../vendor/autoload.php';

use PDFAI\Extractor\OpenAIExtractor;
use PDFAI\PDFAI;
use Dotenv\Dotenv;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was selected for upload
    if (isset($_FILES['fileInput'])) {
        $dotenv = Dotenv::createImmutable('./');
        $dotenv->load();

        $pdfai = new PDFAI(new OpenAIExtractor($_ENV['OPENAI_API_KEY']));
        
        $print = [];
        try {
            $dataExtracted = $pdfai->extract($_POST['textInput'], $_FILES['fileInput']['tmp_name']);
        } catch (\Exception $e) {
            $print = ['error' => $e->getMessage()];
        }
        

        foreach ($dataExtracted as $extractedDatum) {
            $print[] = $extractedDatum->print();
        }
        
        echo json_encode($print);
    } else {
        echo 'No file selected for upload.';
    }
}
