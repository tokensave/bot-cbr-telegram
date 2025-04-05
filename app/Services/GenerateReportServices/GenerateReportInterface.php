<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services\GenerateReportServices;

interface GenerateReportInterface
{
    public function generateReport(array $data): string;
}
