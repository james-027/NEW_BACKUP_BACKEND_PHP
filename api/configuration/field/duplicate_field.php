<?php
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
$databaseName2 = "dws_db_2025";
$dbConnection = new DatabaseConnection($databaseName2);
$processDb = $dbConnection->getEntityManager(); 
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){ 
        $field_loop = new field_loop();
        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
        $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
        $setFieldLoop = $field_loop->setLoopfield($entityManager,$processDb,$field,$task,true);
    }
        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Field Duplicate Complete!"]);
        }
    
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }