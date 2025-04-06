<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ReportGenerate;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GeneratePdfReportAction
{
    /**
     * Генерирует PDF-отчёт, сохраняет файл и возвращает путь до файла.
     *
     * @param array $data Массив с ключами:
     *                    - 'companies': данные компаний,
     *                    - 'non_vat_percentage': процент платежей без НДС.
     * @return string Абсолютный путь до сгенерированного файла.
     */
    public function __invoke(array $data): string
    {
        // Передаём в шаблон как companies и nonVatPercentage
        $pdf = Pdf::loadView('reports.company_table', [
            'companies' => $data['companies'] ?? [],
            'nonVatPercentage' => $data['non_vat_percentage'] ?? 0,
        ]);

        $fileName = 'report' . time() . '.pdf';
        $relativePath = "tmp/companies/{$fileName}";

        // Сохраняем PDF как строку
        Storage::disk('public')->put($relativePath, $pdf->output());

        // Возвращаем абсолютный путь до файла
        return Storage::disk('public')->path($relativePath);
    }
}
