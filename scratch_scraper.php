<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fetcher = app(App\Services\ArticleFetcherService::class);
try {
    $res = $fetcher->fetch('https://nasional.kompas.com/read/2026/05/02/11452181/puan-ingin-satgas-phk-mampu-antisipasi-potensi-gelombang-phk?source=headline');
    echo json_encode($res, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
