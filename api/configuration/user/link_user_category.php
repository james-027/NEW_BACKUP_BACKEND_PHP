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
        $user = $entityManager->find(configuration\user::class,$input['user_id']);
        $categoryIds = $input['category_id']; 

        foreach($user->getUsercategory() as $category){
            $user->removeUsercategory($user->getUsercategory(),$category);
            $entityManager->flush();
        }
        foreach ($categoryIds as $categoryId) {
            $category = $entityManager->find(configuration\category::class, $categoryId);
            if ($category) {
                $user->setUsercategory($category); 
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