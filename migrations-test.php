<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\DBAL\DriverManager;

require_once __DIR__.'/vendor/autoload.php';

$connection = DriverManager::getConnection([
    'url' => $_ENV['DATABASE_URL'],
]);

$config = new PhpFile(__DIR__.'/migrations.php');

$dependencyFactory = DependencyFactory::fromConnection(
    $config,
    new ExistingConnection($connection)
);

$dependencyFactory->getMetadataStorage()->ensureInitialized();

echo "Metadata storage OK\n";
