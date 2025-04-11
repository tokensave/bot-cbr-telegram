<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services;

use App\Actions\ExcelParser\AmountExtractorAction;
use App\Actions\ExcelParser\ExcelTextExtractorAction;
use App\Actions\ExcelParser\InnExtractorAction;
use App\Actions\ExcelParser\VatRowsSeparatorAction;
use App\Actions\ExcelParser\VatSummaryCalculatorAction;

class ExcelParserService
{
    public function parse(string $filePath): array
    {
        $rows = app(ExcelTextExtractorAction::class)($filePath);

        $inns = app(InnExtractorAction::class)($rows);

        $vatRows = app(VatRowsSeparatorAction::class)($rows);
        $withVatAmounts = app(AmountExtractorAction::class)($vatRows['with_vat']);
        $withoutVatAmounts = app(AmountExtractorAction::class)($vatRows['without_vat']);
        $nonVatPercentage = app(VatSummaryCalculatorAction::class)(
            $withVatAmounts,
            $withoutVatAmounts
        );

        return [
            'innList' => $inns,
            'non_vat_percentage' => $nonVatPercentage,
        ];
    }
}
