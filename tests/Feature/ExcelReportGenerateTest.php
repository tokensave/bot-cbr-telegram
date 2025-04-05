<?php

declare(strict_types=1);
declare(ticks=1000);

namespace Tests\Feature;

use App\Services\CompaniesService;
use App\Services\ExcelParserService;
use App\Services\GenerateReportServices\ExcelReportService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExcelReportGenerateTest extends TestCase
{
    private ExcelReportService $service;
    private ExcelParserService $serviceParser;
    private CompaniesService $companiesService;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ExcelReportService();
        $this->serviceParser = new ExcelParserService();
        $this->companiesService = new CompaniesService();
    }

    public function testGenerateExcelReport(): void
    {
        // Пример ИНН — можно подставить любые
        $innList = ['7707083893', '7708236451'];

        // Получаем данные по компаниям
        $companies = $this->companiesService->getCompany($innList);

        // Генерируем отчёт и получаем путь до созданного файла
        $filePath = $this->service->generateReport($companies);

        // Проверяем, что файл существует и не пустой
        $this->assertFileExists($filePath);
        $this->assertGreaterThan(0, filesize($filePath));

        // Удаляем файл после теста
        File::delete($filePath);
    }
}
