<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Enums;

enum ReportFormatEnum: string
{
    case EXCEL = 'EXCEL';
    case PDF = 'PDF';
}
