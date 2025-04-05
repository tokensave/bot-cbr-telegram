<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Services;

use App\Actions\ExcelParser\ParseAction;

class ExcelParserService
{
    public function parse(string $filePath): array
    {
        return app(ParseAction::class)($filePath);
    }

}
