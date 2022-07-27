<?php

// cli-config.php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/app/settings.php';
$settings($containerBuilder);

$doctrine = require_once __DIR__ . '/app/bootstrap.php';
$doctrine($containerBuilder);

/** @var ContainerInterface $container */
$container = $containerBuilder->build();

return ConsoleRunner::createHelperSet($container->get(EntityManager::class));
