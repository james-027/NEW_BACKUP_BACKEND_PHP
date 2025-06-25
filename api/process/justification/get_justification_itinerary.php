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
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();            
            $itinerary = $processDb->find(configuration_process\itinerary::class, $input['itinerary_id']);
            $justification_list = [];
            foreach($itinerary->getItineraryjustification() as $justification){
                $user =$entityManager->find(configuration\user::class, $justification->getCreatedby()->getId());
                $justification_list[] = [
                    "id" =>$justification->getId(),
                    "description" =>$justification->getDescription(),
                    "date_created" =>$justification->getDatecreated()->format('Y-m-d H:i:s'),
                    "created_by" => $user->getFirstname() . " " . $user->getLastname(),  
                ];
            }
            echo header("HTTP/1.1 200 OK");
            echo json_encode($justification_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }