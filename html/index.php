<?php

use App\Controller\Register;
use App\Controller\Admin;
use Base\Application;
use Base\Route;


include '../src/config.php';

include '../vendor/autoload.php';

require '../src/New_DB.php';

$route = new Route();
$route->addRoute('/', Register::class);
$route->addRoute('/admin', Admin::class);


$app = new Application($route);
$app->run();