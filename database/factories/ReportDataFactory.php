<?php

namespace Database\Factories;

use App\Enums\ReportDataEnums\ColorRiskLevelEnum;
use App\Enums\ReportDataEnums\CompanyRiskLevelEnum;
use App\Models\ReportData;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportDataFactory extends Factory
{
    protected $model = ReportData::class;

    public function definition(): array
    {
        return [
            'inn' => $this->faker->unique()->randomNumber(),
            'name' => $this->faker->company(),
            'description' => $this->faker->text(),
            'check_result' => $this->faker->text(10),
            'risk_level' => CompanyRiskLevelEnum::VALID_RISK_LEVEL->value,
            'color_code' => ColorRiskLevelEnum::GREEN->value,
            'last_check_date' => now(),
        ];
    }
}
