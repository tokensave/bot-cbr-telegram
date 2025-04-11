<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

class InnExtractorAction
{
    /**
     * Извлекает из текста все ИНН – последовательности из 10 цифр.
     *
     * @param array $rows
     * @return array Массив ИНН
     */
    public function __invoke(array $rows): array
    {
        $text = implode(' ', $rows);

        preg_match_all('/\b\d{10}\b/', $text, $matches);

        return array_values(array_unique($matches[0]));
    }
}
