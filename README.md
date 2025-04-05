1. Необходимо установить библиотеки:
    - spatie/simple-excel
    - barryvdh/laravel-dompdf
    - defstudio/telegraph
2. Запустить импорт всех неблагонадежных компаний
    - php artisan fetch:companies
2. Добавить в config/filesystem(для локальной разработки) :
    'private' => [
       'driver' => 'local',
       'root' => storage_path('app/private'),
      ]
3. Создать телеграм бота и выполнить команды:
    - php artisan telegraph:new-bot
    - php artisan telegraph:set-webhook {bot_id}

