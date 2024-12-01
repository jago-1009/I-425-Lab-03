<?php
include 'config/credentials.php';
include 'vendor/autoload.php';
use Movies\Authentication\Authenticator as Authenticator;
use Illuminate\Database\Capsule\Manager as Capsule;
use Movies\Middleware\Logging as Logging;
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(['settings' => $config]);
$capsule = new Capsule();
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $db_host,
    'database'  => $db_name,
    'username'  => $db_user,
    'password'  => $db_pass,
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    "prefix"    => "",

]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container = $app->getContainer();
$container['db'] = function ($c) use ($capsule) {
    return $capsule;
};

require __DIR__ . '/config/routes.php';

//var_dump($container['db']);