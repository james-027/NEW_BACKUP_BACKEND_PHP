<?php
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


function parseDate($dateStr) {
    return  DateTime::createFromFormat('Y-m-d\TH:i',$dateStr) ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){    
            $token = json_decode(getBearerToken(), true);
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $entityManager = $dbConnection->getEntityManager();
                        $new_preemptive = new configuration_process\preemptive();
                        $new_preemptive->setStore($input["store_id"]);
                        $new_preemptive->setUser($input["user_id"]);
                        $new_preemptive->setRemark($input["remark"]);
                        $date_planned = parseDate($input["date_planned"]);
                        $actual_planned =parseDate($input["actual_planned"]);
                        $new_preemptive->setDateplanned($date_planned);
                        $new_preemptive->setDateactual($actual_planned);
                        $timezone = new DateTimeZone('Asia/Manila');
                        $date = new DateTime('now', $timezone);
                        $new_preemptive->setDateCreated($date);
                        $new_preemptive->setCreatedby($token['user_id']);
                        $entityManager->persist($new_preemptive);
                $entityManager->flush();
                echo header("HTTP/1.1 200 OK");
                echo json_encode(['Message' => "Successfully Created"]);
           
        }
        
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }