<?php
declare(strict_types=1);
/**
 * @package deploy-task
 *
 * @author  Dmitriy Kuts <me@exileed.com>
 * @link    http://exileed.com
 *
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$container = new League\Container\Container;

$container->delegate(
    new League\Container\ReflectionContainer
);

$container->add(\Psr\Log\LoggerInterface::class, function () {
    $log = new Logger('logger');
    $log->pushHandler(new StreamHandler(__DIR__.'/../logs/app.logs', Logger::WARNING)); //@todo file log in config
});


return $container;