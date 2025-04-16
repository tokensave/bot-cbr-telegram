<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

class AmountExtractorAction
{
    /**
     * Метод извлекает суммы из массива строковых данных
     *
     * @param array $rows Массив строк с информацией о платежах
     * @return array Масив найденных валидных сумм
     */
    public function __invoke(array $rows): array
    {
        $validAmounts = [];

        foreach ($rows as $row) {
            preg_match_all('/(?<![\w])[0-9]+(?:\.[0-9]{1,2}|,[0-9]{1,2})?(?![\w])/u', $row, $matches);

            foreach ($matches[0] as $rawNumber) {
                // Нормализуем число, заменяя запятую на точку
                $normalizedNumber = str_replace(',', '.', $rawNumber);

                // Приводим число к типу float
                $floatValue = (float)$normalizedNumber;

                // Определяем длину целой части числа
                $integerPartLength = strlen((string)((int)$floatValue));

                // Фильтруем суммы:
                // 1. Целая часть не длиннее 7 символов
                // 2. Значение суммы больше 100
                // 3. Первая цифра не равна нулю
                // 4. Нет форматов дат (например, 02.10.) и годов
                if (
                    $floatValue > 100 &&
                    $integerPartLength <= 7 &&
                    isset($rawNumber[0]) && $rawNumber[0] != '0' &&
                    strlen($rawNumber) <= 15 &&
                    !preg_match('/^\d{1,2}\.\d{1,2}\./', $rawNumber) &&
                    !(is_numeric($rawNumber) && ((int)$rawNumber >= 1900 && (int)$rawNumber <= date("Y") + 1))
                ) {
                    $validAmounts[] = number_format($floatValue, 2, '.', '');
                }
            }
        }

        return $validAmounts;
    }
}
