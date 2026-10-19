<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$roles = \Spatie\Permission\Models\Role::pluck('name');
echo json_encode($roles);
