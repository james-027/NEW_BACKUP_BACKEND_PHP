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
        $category = $entityManager->find(configuration\category::class,$input['category_id']);
        $link_list = [];
        if($category){
            foreach($category->getCategorylink() as $link){
                $link_list[] = [
                    'id' => $link->getId(),
                    'description' => $link->getDescription(),
                    'type' => $link->getCategorytype()->getDescription()
                ];
        }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($link_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

}