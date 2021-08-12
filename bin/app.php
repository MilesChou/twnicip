<?php

use App\Commands\Check;
use App\Commands\Update;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->addCommands([
    new Check(),
    new Update(),
]);

return $app->run();
