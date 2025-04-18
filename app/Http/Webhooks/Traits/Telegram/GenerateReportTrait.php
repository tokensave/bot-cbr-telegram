<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Http\Webhooks\Traits\Telegram;

use App\Services\CompaniesService;
use App\Services\ExcelParserService;
use App\Services\GenerateReportServices\GenerateReportFactory;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\StorageException;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait GenerateReportTrait
{
    use Support;
    /**
     * @throws StorageException
     * @throws FileException
     * @throws BindingResolutionException
     */
    protected function parseData(): void
    {
        $reportFormat = $this->chat->storage()->get('report_format');
        $doc = $this->message->document()?->toArray() ?? [];
        $file = Telegraph::store(
            $doc['id'],
            Storage::path('/tmp/companies'),
            $doc['filename']
        );

        $parsedData = $this->parseClientData($file);

        $exportFile = $this->generateReportDeal($reportFormat, $parsedData);

        if (!file_exists($exportFile)) {
            Log::error("Файл для отправки не найден: $exportFile");
            return;
        }

        Log::info("Отправка $reportFormat в Telegram: $exportFile");
        $this->chat->document($exportFile)->send();
        $this->finishExcelReportDeal($exportFile);
    }

    /**
     * @throws StorageException
     */
    public function return(): void
    {
        $this->cancelAction();
    }

    private function parseClientData(string $file): array
    {
        $parsedData = app(ExcelParserService::class)->parse($file);
        $innList = $parsedData['innList'];
        $nonVatPercentage = $parsedData['non_vat_percentage'];
        $companies = app(CompaniesService::class)->getCompany($innList);
        // Объединяем данные для отчёта
        return [
            'companies' => $companies,
            'non_vat_percentage' => $nonVatPercentage,
        ];
    }

    /**
     * @throws BindingResolutionException
     */
    private function generateReportDeal(mixed $reportFormat, array $parseData): string
    {
        $reportFactory = app()->makeWith(GenerateReportFactory::class, ['format' => $reportFormat]);
        // Генерируем отчёт, передавая данные компаний
        return $reportFactory->generateReport()->generateReport($parseData);
    }
}
