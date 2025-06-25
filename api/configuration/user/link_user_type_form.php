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
        $user_type = $entityManager->find(configuration_process\user_type::class,$input['user_type_id']);
        $formIds = $input['form_id']; 

        foreach($user_type->getUsertypeform() as $form){
            $user_type->removeUsertypeform($user_type->getUsertypeform(),$form);
            $entityManager->flush();
        }
        foreach ($formIds as $formId) {
            $form = $entityManager->find(configuration_process\form::class, $formId);
            if ($form) {
                $user_type->setUsertypeform($form); 
            }
        }
        $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Linked Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }