<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$products = App\Models\Product::select('id','slug','name','product_type','status')->latest()->take(20)->get()->toArray();
echo json_encode($products, JSON_PRETTY_PRINT);
