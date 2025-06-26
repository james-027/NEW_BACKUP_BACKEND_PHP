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

function validateRequest($request)
{
    $requiredFields = ["itinerary_type", "store_id", "justification", "schedule"];
    foreach ($requiredFields as $field) {
        if (!isset($request[$field])) {
            return false;
        }
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $entityManager = $dbConnection->getEntityManager();
        if (isset($input) && is_array($input)) {
            foreach ($input as $index => $request) {
                if (validateRequest($request)) {
                    $automation_itinerary = new configuration_process\automation_itinerary();
                    $automation_itinerary->setItinerarytype($request["itinerary_type"]);
                    $automation_itinerary->setJustification($request["justification"]);
                    $parsedDate = DateTime::createFromFormat('Y-m-d', $request["schedule"]);
                    $automation_itinerary->setSchedule($parsedDate);
                    $automation_itinerary->setStore($request["store_id"]);
                    $timezone = new DateTimeZone('Asia/Manila');
                    $date = new DateTime('now', $timezone);
                    $automation_itinerary->setDatecreated($date);
                    $automation_itinerary->setCreatedby($token['user_id']);
                    $automation_itinerary->setProcess(0);
                    $entityManager->persist($automation_itinerary);
                } else {
                    $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                    $response['body'] = json_encode(['Message' => "Invalid Input"]);
                    echo json_encode($response);
                    exit;
                }
            }
            $entityManager->flush();
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message
                ' => "Successfully Created"]);
        } else {
            echo header("HTTP/1.1 400 Bad Request");
            echo json_encode(['Message' => "Invalid Input"]);
        }
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
