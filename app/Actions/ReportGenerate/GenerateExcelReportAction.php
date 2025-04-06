<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ReportGenerate;

use Illuminate\Support\Facades\Log;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use Spatie\SimpleExcel\SimpleExcelWriter;

/**
 * Генерирует Excel-отчёт с данными компаний и итоговым процентом платежей без НДС.
 *
 * @param array $data Массив с ключами:
 *                    - 'companies': данные компаний,
 *                    - 'non_vat_percentage': процент платежей без НДС.
 * @return string Путь до сгенерированного файла.
 */
class GenerateExcelReportAction
{
    public function __invoke(array $data): string
    {
        $companies = $data['companies'] ?? [];
        $nonVatPercentage = $data['non_vat_percentage'] ?? 0;

        $uniqueName = 'report' . time() . '.xlsx';
        $pathToFile = storage_path("app/private/tmp/companies/{$uniqueName}");

        $writer = SimpleExcelWriter::create($pathToFile);

        // Заголовки
        $writer->addHeader(['ИНН', 'Наименование', 'Описание']);

        // Данные компаний
        foreach ($companies as $company) {
            $backgroundColor = defined("OpenSpout\\Common\\Entity\\Style\\Color::" . strtoupper($company['color_code']))
                ? constant("OpenSpout\\Common\\Entity\\Style\\Color::" . strtoupper($company['color_code']))
                : Color::WHITE;

            $style = (new Style())
                ->setBackgroundColor($backgroundColor);

            $writer->addRow([
                $company['inn'] ?? '',
                $company['name'] ?? '',
                $company['description'] ?? '',
            ], $style);
        }

        // Итоговая строка с процентом
        $writer->addRow([
            'Итого',
            'Платежи без НДС:',
            round($nonVatPercentage, 2) . '%',
        ]);

        $writer->close();
        return $pathToFile;
    }
}
