<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){  
            $repository = $entityManager->getRepository(configuration_process\field_type::class);
            $existing = $repository->findOneBy(['description' => $input['description']]);
            if(!$existing){
                $field_type = $entityManager->find(configuration_process\field_type::class,$input['field_type_id']);
                $field_type->setDescription($input['description']);
                $field_type->setIcon($input['icon']);
                $field_type->setLabel($input['label']);
                $entityManager->flush();
                echo header("HTTP/1.1 200 OK");
                echo json_encode(['Message' => $input['label']. " updated"]);
            }else{
                header('HTTP/1.1 409 Conflict'); 
                echo json_encode(["Message"=> $input['description']." already exist"]);
            }
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    