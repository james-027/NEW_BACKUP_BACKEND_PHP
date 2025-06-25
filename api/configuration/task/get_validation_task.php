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
            $token = json_decode(getBearerToken(),true);
            $task = $entityManager->find(configuration_process\task::class, $input['task_id']);
            $validation_list= [];
            foreach($task->getTaskvalidation() as $validation){
                $created_by = null;
                if($validation->getCreatedby()){
                    $createdby = $entityManager->find(configuration\user::class,$validation->getCreatedby());
                    $created_by = $createdby->getFirstname()." ".$createdby->getLastname();
                }
                $user_type = $entityManager->find(configuration_process\user_type::class,$validation->getUsertype());
                $validation_list[] = [
                    "id"=>$validation->getId(),
                    "user_type" =>$user_type->getDescription(),
                    "created_by"=>$created_by,
                    "valid"=>$validation->getValid()
                ];
            }
            echo header("HTTP/1.1 200 OK");
            echo json_encode($validation_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }