<?php

declare(strict_types=1);
declare(ticks=1000);

namespace App\Http\Webhooks;

use App\Enums\ReportFormatEnum;
use App\Http\Webhooks\Traits\Telegram\GenerateReportTrait;
use App\Http\Webhooks\Traits\Telegram\Support;
use App\Models\LogTelegram;
use DefStudio\Telegraph\Exceptions\FileException;
use DefStudio\Telegraph\Exceptions\StorageException;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;

class TelegramWebhook extends WebhookHandler
{
    use Support, GenerateReportTrait;

    /**
     * @throws StorageException
     */
    public function start($edit = 1): void
    {
        $this->clearStorage();
        $labels = ReportFormatEnum::cases();
        $buttons = [];
        foreach ($labels as $label) {
            $buttons[] = Button::make($label->value)
                ->action('awaitFile')
                ->param('report_format', $label->value);
        }
        $keyboard = Keyboard::make()->buttons($buttons);
        $this->sendMsg('Добро пожаловать! В каком формате вам приготовить отчет', $keyboard, $edit);
    }

    /**
     * @throws StorageException
     */
    public function awaitFile(): void
    {
        $report_format = $this->data->get('report_format');
        $this->chat->storage()->set('report_format', $report_format);
        $keyboard = Keyboard::make()->buttons([
            Button::make('Отмена')
                ->action('cancelDeal')
        ]);
        $this->sendMsg('Ожидаем файл Excel', $keyboard);
    }

    protected function handleChatMessage(Stringable $text): void
    {
        try {
            $this->parseData();
        } catch (FileException|StorageException|BindingResolutionException $e) {
            Log::info('Telegram webhook error: ' . $e->getMessage(),['trace' => $e->getMessage()]);
            $keyboard = Keyboard::make()->buttons([
                Button::make('Вернутся в начало')
                ->action('cancelDeal')
            ]);
            $this->sendMsg('Что то пошло не так. Отправьте это сообщение разработчику: ' . $e->getMessage(), $keyboard);
        }
    }

    /**
     * @throws StorageException
     */
    public function cancelDeal(): void
    {
        $this->cancelAction();
    }
}
