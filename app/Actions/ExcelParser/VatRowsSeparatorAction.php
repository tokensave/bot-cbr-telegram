<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ExcelParser;

/**
 * Находит строки, содержащие "с НДС" и "без НДС".
 */
class VatRowsSeparatorAction
{
    /**
     * @param array $rows Строки операций
     * @return array [
     *     'with_vat' => [...],
     *     'without_vat' => [...]
     * ]
     */
    public function __invoke(array $rows): array
    {
        $withVat = [];
        $withoutVat = [];

        $withVatPatterns = config('vat_patterns.with_vat');
        $withoutVatPatterns = config('vat_patterns.without_vat');

        foreach ($rows as $rowText) {
            if ($this->matchesAny($rowText, $withoutVatPatterns)) {
                $withoutVat[] = $rowText;
            } elseif ($this->matchesAny($rowText, $withVatPatterns)) {
                $withVat[] = $rowText;
            }
        }

        return [
            'with_vat' => $withVat,
            'without_vat' => $withoutVat,
        ];
    }

    private function matchesAny(string $text, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }
}
