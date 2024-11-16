<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,  // Display detailed errors
        'logErrors' => true,
        'logErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);


