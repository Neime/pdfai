<?php

declare(strict_types=1);

namespace PDFAI\Domain;

enum UploadType: string
{
    case LOCAL = 'local';
    case URL = 'url';
}
