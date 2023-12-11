<?php

declare(strict_types=1);

namespace PDFAI\Util;

enum ResultType: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
