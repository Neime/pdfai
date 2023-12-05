<?php

header("Access-Control-Allow-Origin: *");

include __DIR__.'/../../vendor/autoload.php';
use App\GetPdfContent;
use App\UploadType;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was selected for upload
    if (isset($_FILES['file'])) {
        $result = (new GetPdfContent())(UploadType::LOCAL, $_FILES['file']['tmp_name']);

        $print = $result->print();

        if ($result->isSuccess()) {
            $print['data'] = null;
        }
        
        echo json_encode($print);
    } else {
        echo 'No file selected for upload.';
    }
}
