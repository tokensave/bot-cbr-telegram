<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services\GenerateReportServices;

use App\Actions\ReportGenerate\GeneratePdfReportAction;

class PdfReportService implements GenerateReportInterface
{
    public function generateReport(array $data): string
    {
        return app(GeneratePdfReportAction::class)($data);
    }
}
