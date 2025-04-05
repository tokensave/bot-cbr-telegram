<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Http\Webhooks\Traits\Telegram;

use App\Enums\LogTelegram\LogTelegramEnum;
use App\Models\LogTelegram;
use DefStudio\Telegraph\Client\TelegraphResponse;
use DefStudio\Telegraph\Exceptions\StorageException;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Storage;

trait Support
{
    /**
     * @throws StorageException
     */
    protected function clearStorage(): void
    {
        $this->chat->storage()->forget('report_format');
    }

    /**
     * @throws StorageException
     */
    public function cancelAction(): void
    {
        $this->clearStorage();
        $this->start(1);
    }

    protected function sendMsg(string $msg, ?Keyboard $keyboard = null, $edit = 1): void
    {
        if ($keyboard) {
            if ($edit) {
                $response = $this->chat->edit($this->messageId)->html($msg)->keyboard($keyboard)->withoutPreview(
                )->send();
            } else {
                $response = $this->chat->html($msg)->keyboard($keyboard)->withoutPreview()->send();
            }
        } elseif ($edit) {
            $response = $this->chat->edit($this->messageId)->html($msg)->withoutPreview()->send();
        } else {
            $response = $this->chat->html($msg)->withoutPreview()->send();
        }
        $this->loggerResponse($response);
    }

    protected function loggerResponse(TelegraphResponse $response): void
    {
        if ($response->telegraphError()) {
            LogTelegram::create([
                'telegraph_chat_id' => $this->chat->id,
                'data' => $response->body(),
            ]);
        }
        if ($response->telegraphOk()) {
            LogTelegram::create([
                'type' => LogTelegramEnum::SEND,
                'telegraph_chat_id' => $this->chat->id,
                'data' => $response->body(),
            ]);
        }
    }

    protected function finishExcelReportDeal(string $exportFile): void
    {
        Storage::delete($exportFile);

        $keyboard = Keyboard::make()->buttons([
            Button::make('Вернутся в начало')
                ->action('cancelAction')
        ]);
        $this->chat->message('Что делаем далее?')->keyboard($keyboard)->send();
    }
}
