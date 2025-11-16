<?php
require './vendor/autoload.php'; // in root directory //

use App\Core\Config;
use App\Core\Application;

Config::getInstance();

$app = new Application();
