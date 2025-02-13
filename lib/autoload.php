<?php

require __DIR__ . '/NebuLoader/forceLoad.php';


$app = new \NebuLoader\Application(dirname(__DIR__));

$app->registerAutoloader(
    $app->loader->classLoader()
);