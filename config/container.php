<?php declare(strict_types=1);


use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$container = new League\Container\Container;

$container->delegate(
    new League\Container\ReflectionContainer
);

$container->add(\Psr\Log\LoggerInterface::class, function () {
    $log = new Logger('logger');
    $log->pushHandler(new StreamHandler(__DIR__.'/../logs/app.logs', Logger::WARNING)); //@todo file log in config
    return $log;
});

$container->add(\App\SSH\SSH2Connection::class,\App\SSH\SSH2Client::class);

return $container;