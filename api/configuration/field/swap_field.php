<?php
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
$check = false;
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){   
            $origin = $entityManager->find(configuration_process\field::class, $input['field_origin_id']);
            $destination = $entityManager->find(configuration_process\field::class, $input['field_destination_id']);
            $origin_row = $origin->getRowno();
            $origin_col = $origin->getColno();
            $destination_row = $destination->getRowno();
            $destination_col = $destination->getColno();
            $origin->setRowno($destination_row);
            $origin->setColno($destination_col);
            $destination->setRowno($origin_row);
            $destination->setColno($origin_col);
            $entityManager->flush();    
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Field Swap Successfully"]);
        }
    }
else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
}