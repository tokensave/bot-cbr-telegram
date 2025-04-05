<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Enums\ReportDataEnums;


enum ColorRiskLevelEnum: string
{
    case GREEN = 'green';
    case RED = 'red';
}
