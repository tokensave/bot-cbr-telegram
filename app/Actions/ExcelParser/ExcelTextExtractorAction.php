<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExcelTextExtractorAction
{
    /**
     * Читает Excel-файл и возвращает массив строк, где каждая строка соответствует одной операции.
     *
     * @param string $filePath
     * @return array Массив строк
     */
    public function __invoke(string $filePath): array
    {
        try {
            $rows = SimpleExcelReader::create($filePath)
                ->noHeaderRow()
                ->getRows()
                ->toArray();

            if (empty($rows)) {
                Log::warning('Файл пустой!');
                return [];
            }

            // Для каждой строки объединяем ячейки в одну строку
            return array_map(static function (array $row) {
                // Приводим каждую ячейку к строке с нормализацией даты
                $cells = array_map(static function ($value) {
                    return $value instanceof \DateTimeImmutable
                        ? $value->format('Y-m-d')
                        : (string)$value;
                }, $row);
                // Объединяем ячейки через пробел, нормализуем пробелы
                $text = mb_strtolower(implode(' ', $cells));
                $text = str_replace("\xc2\xa0", ' ', $text);
                return preg_replace('/\s+/', ' ', $text);
            }, $rows);
        } catch (Throwable $e) {
            Log::error("Ошибка чтения Excel-файла: {$e->getMessage()}");
            throw new \RuntimeException("Ошибка обработки файла: {$e->getMessage()}");
        }
    }
}
