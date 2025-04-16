<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

/**
 * Подсчитывает процент от суммы операций без НДС к общей сумме.
 */
class VatSummaryCalculatorAction
{
    /**
     * @param array $withVatAmounts
     * @param array $withoutVatAmounts
     * @return float Процент без НДС к общему обороту
     */

    public function __invoke(array $withVatAmounts, array $withoutVatAmounts): float
    {
        // Просто очищаем каждое значение в обоих массивах
        $normalizeValues = static function (array $values): array {
            return array_map(static function (string $value) {
                return (float) str_replace([' ', ','], ['', '.'], $value);
            }, $values);
        };

        // Применяем очистку к обоим массивам
        $withVatFlat = $normalizeValues($withVatAmounts);
        $withoutVatFlat = $normalizeValues($withoutVatAmounts);

        // Высчитываем суммы
        $withVatSum = array_sum($withVatFlat);
        $withoutVatSum = array_sum($withoutVatFlat);

        // Рассчитываем общий оборот и процент без НДС
        $total = $withVatSum + $withoutVatSum;

        return $total > 0 ? round(($withoutVatSum / $total) * 100, 2) : 0.0;
    }
}
