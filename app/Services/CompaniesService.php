<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services;

use App\Actions\Company\GetCompanyAction;
use App\Actions\Company\ImportCompanyFromApiAction;

class CompaniesService
{
    public function importCompany(array $companyData): void
    {
        app(ImportCompanyFromApiAction::class)($companyData);
    }

    public function getCompany(array $companyData): array
    {
        return app(GetCompanyAction::class)($companyData);
    }
}
