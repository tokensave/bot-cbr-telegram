<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

class AmountExtractorAction
{
    /**
     * Извлекает суммы из строк.
     *
     * @param array $rows
     * @return array Массив сумм
     */

    public function __invoke(array $rows): array
    {
        $amounts = [];

        foreach ($rows as $row) {
            if (preg_match_all('/\b\d{1,3}(?:[.,\s]?\d{3})*[.,]\d{2}\b/', $row, $matches)) {
                $amounts[] = $matches[0];
            }
        }

        return $amounts;
    }
}
