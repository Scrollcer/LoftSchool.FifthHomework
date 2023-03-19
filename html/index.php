<?php

use App\Controller\Register;
use Base\Application;
use Base\Route;

include '../src/config.php';

include '../vendor/autoload.php';

$route = new Route();
$route->addRoute('/', Register::class);

$app = new Application($route);
$app->run();