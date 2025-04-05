<?php

namespace App\Console\Commands;

use App\Services\CbrApiService;
use App\Services\CompaniesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchCompaniesCommand extends Command
{
    protected $signature = 'fetch:companies';
    protected $description = 'Обращается к API ЦБ для получения списка всех неблагонадежных компаний';

    private CbrApiService $apiService;
    private CompaniesService $companyService;

    public function __construct(CbrApiService $apiService, CompaniesService $companyService)
    {
        parent::__construct();
        $this->apiService = $apiService;
        $this->companyService = $companyService;
    }

    public function handle(): void
    {
        $this->info('Процесс импорта компаний начался!');

        $data = $this->apiService->fetchCompanies();

        if (empty($data['RC'])) {
            Log::info("Получены пустые данные, останавливаем процесс.");
            return;
        }

        foreach ($data['RC'] as $company) {
            $this->companyService->importCompany($company);
        }

        $this->info('Процесс импорта компаний завершен успешно!');
    }
}
