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

        // Убеждаемся, что директория есть
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        // Записываем тестовые данные
        SimpleExcelWriter::create($filePath)
            ->addRow(['ИНН'])
            ->addRow(['7707083893'])
            ->addRow(['7708236451'])
            ->close();

        // Тестируем парсинг
        $innList = $this->service->parse($filePath);

        // Проверяем, что вернулся массив с ИНН
        $this->assertIsArray($innList);
        $this->assertCount(2, $innList);
        $this->assertContains('7707083893', $innList);
        $this->assertContains('7708236451', $innList);

        // Удаляем временный файл
        unlink($filePath);
    }
}
