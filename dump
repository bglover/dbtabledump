#!/usr/bin/env php
<?php
require_once 'vendor/autoload.php';
use Access9\Console\Application;

use Access9\Console\Command\ConfigGetCommand;
use Access9\Console\Command\ConfigSetCommand;
use Access9\Console\Command\DumpYamlCommand;

$application = new Application();
$application->add(new ConfigSetCommand());
$application->add(new ConfigGetCommand());
$application->add(new DumpYamlCommand());
$application->run();
