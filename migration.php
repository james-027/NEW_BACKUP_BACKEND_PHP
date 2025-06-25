<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;

require_once "vendor/autoload.php";




$sharedConnectionParams = [
    'user' => 'main',
    'password' => 'nnsnqh9jha5lmyKBu8V3sITrDF3rt2PUGYq3qjDgXpDu8RHXPo',
    'host' => '127.0.0.1',
    'port' => '3306',
    'driver' => 'pdo_mysql',
];


function setupDbAndUpdateSchema($dbName, $configPaths) {
    $dbConfig = ORMSetup::createAttributeMetadataConfiguration(
        paths: $configPaths,
        isDevMode: true
    );


    $dbConnectionParams = array_merge($GLOBALS['sharedConnectionParams'], ['dbname' => $dbName]);
    $dbConnection = DriverManager::getConnection($dbConnectionParams);

    $dbEntityManager = new EntityManager($dbConnection, $dbConfig);

    $dbSchemaTool = new SchemaTool($dbEntityManager);
    $dbClasses = $dbEntityManager->getMetadataFactory()->getAllMetadata();
    $dbSchemaTool->updateSchema($dbClasses, true);

 
    echo "Schema updated for $dbName.\n";
}


$mainDbPaths = [
    __DIR__ . "/src/configuration", 
    __DIR__ . "/src/configuration_process"
];

$secondDbPaths = [
    __DIR__ . "/src/process", 
    __DIR__ . "/src/configuration_process"
];

$thirdDbPaths = [
    __DIR__ . "/src/temperature"
];


setupDbAndUpdateSchema('main_db', $mainDbPaths);
setupDbAndUpdateSchema('dws_db_2025', $secondDbPaths);
setupDbAndUpdateSchema('temperature_db_2025', $thirdDbPaths);


?>
