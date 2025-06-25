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
        $itinerary_type = $entityManager->find(configuration_process\itinerary_type::class,$input['itinerary_type_id']);
        $form_list = [];
            foreach($itinerary_type->getItinerarytypeform() as $form){
                $form_list[] = [
                    'id' => $form->getId(),
                    'title' => $form->getTitle(),
                ];
            }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($form_list);
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

}

