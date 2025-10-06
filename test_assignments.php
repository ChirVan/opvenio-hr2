<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignments = App\Modules\learning_management\Models\AssessmentAssignment::on('learning_management')->count();
echo 'Total assignments: ' . $assignments . PHP_EOL;

$recent = App\Modules\learning_management\Models\AssessmentAssignment::on('learning_management')->latest()->take(3)->get();
foreach($recent as $assignment) {
    echo 'ID: ' . $assignment->id . ', Employee: ' . $assignment->employee_name . ', Status: ' . $assignment->status . PHP_EOL;
}