<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

class ParseAction
{
    /**
     * Парсит входящий файл Excel
     *
     * @param string $filePath
     * @return array
     */
    public function __invoke(string $filePath): array
    {
        try {
            // Читаем весь файл (получаем массив всех строк)
            $rows = SimpleExcelReader::create($filePath)->noHeaderRow()->getRows()->toArray();

            if (empty($rows)) {
                Log::warning('Файл пустой!');
                return [];
                // добавить обработку через телеграмм
            }

            // Собираем все строки в один большой текст
            $allText = implode(" ", array_reduce($rows, static function ($carry, $row) {
                $processedRow = array_map(static function ($value) {
                    return ($value instanceof \DateTimeImmutable) ? $value->format('Y-m-d') : $value;
                }, array_values($row));

                return array_merge($carry, array_keys($row), $processedRow);
            }, []));

            // Ищем все ИНН (10 цифр подряд)
            preg_match_all('/\b\d{10}\b/', $allText, $matches);

            // Удаляем дубликаты и перенумеровываем массив
            return array_values(array_unique($matches[0]));
        } catch (Throwable $e) {
            Log::error("Ошибка парсинга файла: {$e->getMessage()}");
            throw new \RuntimeException("Ошибка обработки файла: {$e->getMessage()}");
        }
    }

}
