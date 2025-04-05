<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ReportGenerate;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GeneratePdfReportAction
{
    /**
     * Генерирует PDF-отчёт, сохраняет файл и возвращает строку пути до файла.
     *
     * @param array $data Массив данных для отчёта (например, список компаний)
     * @return string Путь до сгенерированного файла
     */
    public function __invoke(array $data): string
    {
        $pdf = Pdf::loadView('reports.company_table', ['companies' => $data]);

        $fileName = 'report' . time() . '.pdf';
        $relativePath = "tmp/companies/{$fileName}";

        // Сохраняем PDF как строку
        Storage::disk('private')->put($relativePath, $pdf->output());

        // Возвращаем абсолютный путь до файла
        return Storage::disk('private')->path($relativePath);
    }
}
