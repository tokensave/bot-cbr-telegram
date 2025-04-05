1. Запустить импорт всех неблагонадежных компаний
    - php artisan fetch:companies
2. Добавить в config/filesystem(для локальной разработки) :
    'private' => [
       'driver' => 'local',
       'root' => storage_path('app/private'),
      ]
3. 
