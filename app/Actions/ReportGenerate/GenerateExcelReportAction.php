<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\ReportGenerate;

use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use Spatie\SimpleExcel\SimpleExcelWriter;

/**
 * Генерирует Excel-отчёт с применением стилей для каждой строки.
 *
 * @param array $data Массив данных компаний.
 * @return string
 */
class GenerateExcelReportAction
{
    public function __invoke(array $data): string
    {
        // Указываем путь для сохранения отчёта
        $uniqueName = 'report' . time()  . '.xlsx';
        $pathToFile = storage_path("app/private/tmp/companies/{$uniqueName}");

        // Создаем экземпляр писателя. Если путь оканчивается на .xlsx, то будет создан Excel-файл.
        $writer = SimpleExcelWriter::create($pathToFile);

        // Опционально: задаем заголовки вручную
        $writer->addHeader(['ИНН', 'Наименование', 'Описание']);

        // Проходим по данным и для каждой строки формируем стиль на основе поля color_code
        foreach ($data as $company) {
            // Пример: если значение color_code совпадает с одной из констант OpenSpout, можно использовать его напрямую.
            // Если нет, можно сделать сопоставление (например, #00FF00 -> Color::GREEN)
            // Здесь для примера предполагается, что color_code содержит корректное значение для OpenSpout, например, 'GREEN'
            $backgroundColor = defined("OpenSpout\\Common\\Entity\\Style\\Color::" . strtoupper($company['color_code']))
                ? constant("OpenSpout\\Common\\Entity\\Style\\Color::" . strtoupper($company['color_code']))
                : Color::WHITE;

            // Создаем стиль для всей строки (можно применить стиль и к отдельной ячейке, если нужно)
            $style = (new Style())
                ->setBackgroundColor($backgroundColor);

            $writer->addRow([
                $company['inn'] ?? '',
                $company['name'] ?? '',
                $company['description'] ?? '',
            ], $style);
        }

        // Завершаем запись файла
        $writer->close();
        return $pathToFile;
    }
}
