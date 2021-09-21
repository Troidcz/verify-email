<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Nette\Configurator;
use Tester\Environment;

Environment::setup();

$configurator = new Configurator();

$configurator->setTempDirectory(__DIR__ . '/../temp');

return $configurator->createContainer();
