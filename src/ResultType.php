<?php

declare(strict_types=1);

namespace App;

enum ResultType: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}
