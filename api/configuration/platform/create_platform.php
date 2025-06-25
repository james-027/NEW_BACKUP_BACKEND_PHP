<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){    
      
      
    
        $platform = new configuration_process\platform;
        $repository = $entityManager->getRepository(configuration_process\platform::class);
        $existing = $repository->findOneBy(['description' => $input['description']]);
        if(!$existing){
             $platform->setDescription($input['description']);
        $platform->setIcon($input['icon']);
        $platform->setPath($input['path']);
        $entityManager->persist($platform);
        $entityManager->flush();
        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => "Platform created"]);

        }
        else{
            header('HTTP/1.1 409 Conflict'); 
            echo json_encode(["Message"=> "Platform already exist"]);
        }

        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }