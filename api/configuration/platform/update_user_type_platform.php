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
        $user_type = $entityManager->find(configuration_process\user_type::class,$input['user_type_id']);
        $platforms = $input['platform_id'];
        foreach($user_type->getUsertypeplatform() as $platform){
            $user_type->removeUsertypeplatform($user_type->getUsertypeplatform(),$platform);
            $entityManager->flush();
        }

        foreach($platforms as $platform){
        $new_platform = $entityManager->find(configuration_process\platform::class,$platform);
        $user_type->setUsertypeplatform($new_platform); 
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