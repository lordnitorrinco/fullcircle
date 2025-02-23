<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Cli\Cli;

// Ejecutar el script principal
$Cli = new Cli();
$Cli->run($argv);