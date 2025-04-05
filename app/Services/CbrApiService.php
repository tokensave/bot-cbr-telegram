<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services;

use App\Actions\CbrApi\GetAllCompaniesAction;

class CbrApiService
{
    public function fetchCompanies(): array
    {
        return app(GetAllCompaniesAction::class)();
    }

}
