<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('name', 'like', '%euclaudio%')->first();
if ($user) {
    echo json_encode($user->toArray(), JSON_PRETTY_PRINT);
    echo "\n\nPAYROLLS:\n";
    $payrolls = \App\Models\Payroll::where('user_id', $user->id)->get();
    foreach($payrolls as $p) {
        echo json_encode($p->toArray(), JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "User not found\n";
}
