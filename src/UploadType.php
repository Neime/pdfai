<?php

declare(strict_types=1);

namespace App;

enum UploadType: string
{
    case LOCAL = 'local';
    case URL = 'url';
}
