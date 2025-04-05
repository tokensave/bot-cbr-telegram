<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\Company;

use App\Enums\ReportDataEnums\ColorRiskLevelEnum;
use App\Enums\ReportDataEnums\CompanyRiskLevelEnum;
use App\Models\ReportData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Получает данные json из Апи и сохраняет в базу
 *
 * @param array $companyData
 * @return void
 */
class ImportCompanyFromApiAction
{
    public function __invoke(array $companyData): void
    {
        try {
            DB::transaction(static function () use ($companyData) {
                ReportData::query()->create(
                    [
                        'inn' => $companyData['INN'],
                        'name' => $companyData['Name'],
                        'description' => $companyData['Sign'],
                        'check_result' => json_encode($companyData, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                        'risk_level' => CompanyRiskLevelEnum::INVALID_RISK_LEVEL->value,
                        'color_code' => ColorRiskLevelEnum::RED->value,
                        'last_check_date' => now(),
                    ]
                );
            });
        } catch (\Exception $e) {
            Log::error("Ошибка сохранения компании {$companyData['Name']}: " . $e->getMessage());
        }
    }
}
