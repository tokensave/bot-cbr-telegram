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
        $flatten = static function (array $amountGroups): array {
            return array_map(static function (string $value) {
                return (float) str_replace([' ', ','], ['','.' ], $value);
            }, array_merge(...$amountGroups));
        };

        $withVatFlat = $flatten($withVatAmounts);
        $withoutVatFlat = $flatten($withoutVatAmounts);

        $withVatSum = array_sum($withVatFlat);
        $withoutVatSum = array_sum($withoutVatFlat);

        $total = $withVatSum + $withoutVatSum;

        return $total > 0 ? round(($withoutVatSum / $total) * 100, 2) : 0.0;
    }
}
