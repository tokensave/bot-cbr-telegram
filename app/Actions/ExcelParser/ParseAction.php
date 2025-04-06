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
     * Парсит входящий Excel-файл.
     *
     * Извлекает все ИНН (10 цифр подряд) и вычисляет процентное соотношение операций без НДС.
     *
     * @param string $filePath Путь к Excel-файлу
     * @return array Массив с ключами:
     *               - 'inns': массив найденных ИНН,
     *               - 'non_vat_percentage': процент операций без НДС от общего количества операций.
     */
    public function __invoke(string $filePath): array
    {
        try {
            // Читаем все строки из файла
            $rows = SimpleExcelReader::create($filePath)
                ->noHeaderRow()
                ->getRows()
                ->toArray();

            if (empty($rows)) {
                Log::warning('Файл пустой!');
                return [
                    'innList' => [],
                    'non_vat_percentage' => 0,
                ];
            }

            // Собираем все ячейки в один большой текст для поиска ИНН
            $allCells = array_reduce($rows, static function (array $carry, array $row) {
                $cells = array_map(static function ($value) {
                    return $value instanceof \DateTimeImmutable
                        ? $value->format('Y-m-d')
                        : (string)$value;
                }, array_values($row));
                return array_merge($carry, $cells);
            }, []);
            $allText = mb_strtolower(implode(" ", $allCells));

            // Извлекаем все ИНН (10 цифр подряд)
            preg_match_all('/\b\d{10}\b/', $allText, $innMatches);
            $inns = array_values(array_unique($innMatches[0]));

            // Подсчитываем операции по строкам
            $vatCount = 0;
            $nonVatCount = 0;

            foreach ($rows as $row) {
                // Преобразуем каждую строку в текст с нормализацией пробелов
                $rowText = mb_strtolower(implode(" ", array_map(function ($value) {
                    return $value instanceof \DateTimeImmutable ? $value->format('Y-m-d') : (string)$value;
                }, $row)));
                $rowText = str_replace("\xc2\xa0", ' ', $rowText);
                $rowText = preg_replace('/\s+/', ' ', $rowText);

                // Если строка содержит "без ндс" или "ндс не облагается" – считаем операцию как без НДС
                if (preg_match('/(?:без\s+ндс|ндс\s+не\s+облагается)/iu', $rowText)) {
                    $nonVatCount++;
                }
                // Если строка содержит "в т.ч. ндс" или "с ндс" – считаем операцию как с НДС
                elseif (preg_match('/(?:в\s*т\.?ч\.?\s*ндс|с\s+ндс)/iu', $rowText)) {
                    $vatCount++;
                }
            }
            $totalOperations = $vatCount + $nonVatCount;
            $nonVatPercentage = $totalOperations > 0 ? ($nonVatCount / $totalOperations) * 100 : 0;

            return [
                'innList' => $inns,
                'non_vat_percentage' => $nonVatPercentage,
            ];
        } catch (Throwable $e) {
            Log::error("Ошибка парсинга файла: {$e->getMessage()}");
            throw new \RuntimeException("Ошибка обработки файла: {$e->getMessage()}");
        }
    }
}
