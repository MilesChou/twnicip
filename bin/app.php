<?php

use App\Commands\Check;
use App\Commands\Update;
use App\Commands\Verify;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->addCommands([
    new Check(),
    new Update(),
    new Verify(),
]);

return $app->run();
