<?php

namespace App\Models;

use App\Enums\ReportDataEnums\ColorRiskLevelEnum;
use App\Enums\ReportDataEnums\CompanyRiskLevelEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    use HasFactory;
    protected $fillable = [
        'inn',
        'name',
        'description',
        'last_check_date',
        'check_result',
        'risk_level',
        'color_code'
    ];

    protected $casts = [
        'risk_level' => CompanyRiskLevelEnum::class,
        'color_code' => ColorRiskLevelEnum::class,
        'check_result' => 'json',
        'last_check_date' => 'datetime',
    ];
}
