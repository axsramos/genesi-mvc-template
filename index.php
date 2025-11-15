<?php

require 'vendor/autoload.php';

use App\Core\Config;
use App\Core\Application;

Config::getInstance();

$app = new Application();
