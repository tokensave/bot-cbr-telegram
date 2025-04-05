<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services\GenerateReportServices;

use App\Enums\ReportFormatEnum;
use InvalidArgumentException;

class GenerateReportFactory
{
    private string $format;
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function generateReport(): GenerateReportInterface
    {
        return match ($this->format) {
            ReportFormatEnum::EXCEL->value => app(ExcelReportService::class),
            ReportFormatEnum::PDF->value => app(PdfReportService::class),
            default => throw new InvalidArgumentException("Неизвестный формат: {$this->format}"),
        };
    }
}
