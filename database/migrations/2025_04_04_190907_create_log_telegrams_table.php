<?php

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_telegrams', static function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('failed');
            $table->foreignIdFor(TelegraphChat::class)->constrained()->cascadeOnDelete();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (! app()->isProduction()) {
            Schema::dropIfExists('log_telegrams');
        }
    }
};
