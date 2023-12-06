# PDFAI - Extract Data From PDF with AI

PDFAI is a package to help to extract data from a pdf with GenAI 

It's help to upload pdf file and ask to extract data from it.

## Installation
```
make start
make composer c="install"
```

## Usage

```php
$getPdf = new \PDFAI\GetPdfContent();
$content = $getPdf('path/of/my/file.pdf');

$extractor = new OpenAIExtractor();
$data = (new \PDFAI\ExtractDataFromPdf($extractor, $content);
```

## Website Localhost
(only for endtoend test)
[http://localhost:8080/](http://localhost:8080/)

## Run Test

### Run PhpUnit

Add .env with your openai API key
```
OPENAI_API_KEY=your-api-key
```

```
make unit c="tests"
```

### Test get pdf content

1) Run docker to open access to tests/endtoend/index.php in http://localhost:8080/

2) Open tests/endtoend/index.html in navigator

3) Upload a file

4) wait response
