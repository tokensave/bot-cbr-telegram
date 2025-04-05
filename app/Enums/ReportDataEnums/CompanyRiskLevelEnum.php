<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Enums\ReportDataEnums;

enum CompanyRiskLevelEnum: string
{
    case VALID_RISK_LEVEL = 'VALID_RISK_LEVEL';
    case INVALID_RISK_LEVEL = 'INVALID_RISK_LEVEL';
}
