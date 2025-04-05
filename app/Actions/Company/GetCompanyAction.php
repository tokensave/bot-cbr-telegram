<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\Company;

use App\Enums\ReportDataEnums\ColorRiskLevelEnum;
use App\Enums\ReportDataEnums\CompanyRiskLevelEnum;
use App\Models\ReportData;

class GetCompanyAction
{
    /**
     * Ищет компании по списку ИНН.
     * Если найдена — возвращает массив данных, если нет — задаёт дефолтные значения.
     *
     * @param array $innList Массив ИНН
     * @return array
     */
    public function __invoke(array $innList): array
    {
        $companies = ReportData::whereIn('inn', $innList)->get(
            ['inn', 'name', 'description', 'risk_level', 'color_code']
        )
            ->keyBy('inn')
            ->toArray();

        // Заполняем данные для тех, кого не нашли
        foreach ($innList as $inn) {
            if (!isset($companies[$inn])) {
                $companies[$inn] = [
                    'inn' => $inn,
                    'name' => 'Кампания не найдена в ЧС',
                    'description' => 'Нет описания',
                    'risk_level' => CompanyRiskLevelEnum::VALID_RISK_LEVEL->value,
                    'color_code' => ColorRiskLevelEnum::GREEN->value,
                ];
            }
        }

        return array_values($companies);
    }
}
