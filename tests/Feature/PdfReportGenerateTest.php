<?php

declare(strict_types=1);
declare(ticks=1000);

namespace Feature;

use App\Models\ReportData;
use Illuminate\Support\Facades\File;
use App\Services\GenerateReportServices\PdfReportService;
use Tests\TestCase;

class PdfReportGenerateTest extends TestCase
{
    private PdfReportService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new PdfReportService();
    }

    public function testGeneratePdfReport(): void
    {
        $companies = ReportData::factory()->count(2)->create();
        $data = $companies->toArray();

        // Генерируем PDF
        $filePath = $this->service->generateReport($data);

        // Проверяем, что файл не пустой
        $this->assertGreaterThan(0, filesize($filePath), "PDF-файл пустой");

        // (Опционально) Удаляем после теста
        File::delete($filePath);
    }
}
