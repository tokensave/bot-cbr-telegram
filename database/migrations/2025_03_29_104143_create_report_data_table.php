<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_data', static function (Blueprint $table) {
            $table->id();
            $table->string('inn')->nullable()->index()->comment('ИНН компании');
            $table->string('name')->nullable()->index()->comment('Название компании');
            $table->string('description')->nullable()->comment('Описание риска компании');
            $table->json('check_result')->nullable()->comment('Поле для хранения результата проверки');
            $table->string('risk_level')->comment('Статус риска компании');
            $table->string('color_code')->comment('Поле для хранения цветовой маркировки');
            $table->dateTime('last_check_date')->comment('Последнее обновление');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_data');
    }
};
