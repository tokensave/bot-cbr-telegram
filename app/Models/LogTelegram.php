<?php

namespace App\Models;

use App\Enums\LogTelegram\LogTelegramEnum;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogTelegram extends Model
{
    protected $fillable = [
        'type',
        'telegraph_chat_id',
        'data',
    ];

    protected $casts = [
        'type' => LogTelegramEnum::class,
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(TelegraphChat::class, 'chat_id', 'chat_id');
    }
}
