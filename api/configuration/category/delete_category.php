<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
        if(getBearerToken()){ 
                $remove_origin= $entityManager->find(configuration\category::class,$input['category_id']);
                $repository = $entityManager->getRepository(configuration\table_category::class);
                $table_category = $repository->findOneBy(['category_id' => $remove_origin]);
                $entityManager->remove($table_category); 
                $entityManager->flush();  
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Category Removed"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }