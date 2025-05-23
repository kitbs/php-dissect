<?php

define('DISSECT_VERSION', 'DEV');

if (is_dir($vendor = getcwd().'/vendor')) {
    require $vendor.'/autoload.php';
}

if (is_dir($vendor = __DIR__.'/../vendor')) {
    require $vendor.'/autoload.php';
} elseif (is_dir($vendor = __DIR__.'/../../../../vendor')) {
    require $vendor.'/autoload.php';
} else {
    exit(
        'You must set up the project dependencies.'.PHP_EOL.
        'To do that, run the following commands:'.PHP_EOL.PHP_EOL.
        '$ curl -s https://getcomposer.org/installer | php'.PHP_EOL.
        '$ php composer.phar install'.PHP_EOL
    );
}

if (! class_exists('Symfony\Component\Console\Application')) {
    exit(
        'You must install the symfony/console package in order '.
        'to use the command-line tool.'.PHP_EOL
    );
}

$app = new Dissect\Console\Application(DISSECT_VERSION);
$app->run();
