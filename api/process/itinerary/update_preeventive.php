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


function parseDate($dateStr) {
    return  DateTime::createFromFormat('Y-m-d\TH:i',$dateStr) ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){    
            $token = json_decode(getBearerToken(), true);
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $entityManager = $dbConnection->getEntityManager();
                        $new_preventive = $entityManager->find(configuration_process\preventive::class,$input['preventive_id']);
                        $new_preventive->setStore($input["store_id"]);
                        $new_preventive->setUser($input["user_id"]);
                        $new_preventive->setRemark($input["remark"]);
                        $new_preventive->setItinerary($input["itinerary_id"]);
                        $date_planned = parseDate($input["date_planned"]);
                        $actual_planned =parseDate($input["actual_planned"]);
                        $new_preventive->setDateplanned($date_planned);
                        $new_preventive->setDateactual($actual_planned);
                        $timezone = new DateTimeZone('Asia/Manila');
                        $date = new DateTime('now', $timezone);
                        $new_preventive->setDateCreated($date);
                        $new_preventive->setCreatedby($token['user_id']);
                        $entityManager->persist($new_preventive);
                $entityManager->flush();
                echo header("HTTP/1.1 200 OK");
                echo json_encode(['Message' => "Successfully Updated"]);
           
        }
        
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }