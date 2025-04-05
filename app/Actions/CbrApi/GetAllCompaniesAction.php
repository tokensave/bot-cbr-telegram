<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Actions\CbrApi;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetAllCompaniesAction
{
    public function __invoke(): array
    {
        try {
            $response = Http::get(config('cbr.black_list'));

            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error("Ошибка при запросе списка компаний: " . $e->getMessage());
            return [];
        }
    }
}
