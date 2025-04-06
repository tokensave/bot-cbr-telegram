<?php

declare(strict_types=1);
declare(ticks=1000);

namespace Tests\Feature;

use App\Services\ExcelParserService;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Tests\TestCase;

class ExcelParserTest extends TestCase
{
    private ExcelParserService $service;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ExcelParserService();
    }
    public function testExcelParser(): void
    {
        // Создаём временный Excel-файл
        $filename = 'test_excel_' . time() . '.xlsx';
        $filePath = storage_path("app/private/tmp/companies/{$filename}");

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        // Пишем тестовые данные: два ИНН и одна строка с "без НДС"
        SimpleExcelWriter::create($filePath)
            ->addRow(['ИНН', 'Комментарий'])
            ->addRow(['7707083893', 'без НДС'])
            ->addRow(['7708236451', 'С НДС'])
            ->close();

        // Вызываем парсинг
        $result = $this->service->parse($filePath);

        // Проверка структуры результата
        $this->assertIsArray($result);
        $this->assertArrayHasKey('innList', $result);
        $this->assertArrayHasKey('non_vat_percentage', $result);

        // Проверка ИНН
        $this->assertCount(2, $result['innList']);
        $this->assertContains('7707083893', $result['innList']);
        $this->assertContains('7708236451', $result['innList']);

        // Проверка расчета процента без НДС
        $this->assertEquals(50.0, $result['non_vat_percentage']);

        // Удаляем файл
        unlink($filePath);
    }
}
