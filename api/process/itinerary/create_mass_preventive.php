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
    $requiredFields = ["user_id", "store_id", "remark", "date_planned", "actual_planned"];
    foreach ($requiredFields as $field) {
        if (!isset($request[$field])) {
            return false;
        }
    }
    return true;
}


function parseDate($dateStr)
{
    return  DateTime::createFromFormat('Y-m-d\TH:i', $dateStr) ?: null;
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
                    $preventive = new configuration_process\preventive();
                    $preventive->setStore($request["store_id"]);
                    $preventive->setUser($request["user_id"]);
                    $preventive->setRemark($request["remark"]);
                    $date_planned = parseDate($request["date_planned"]);
                    $actual_planned = parseDate($request["actual_planned"]);
                    $preventive->setDateplanned($date_planned);
                    $preventive->setDateactual($actual_planned);
                    $timezone = new DateTimeZone('Asia/Manila');
                    $date = new DateTime('now', $timezone);
                    $preventive->setDateCreated($date);
                    $preventive->setCreatedby($token['user_id']);
                    $entityManager->persist($preventive);
                } else {
                    $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                    $response['body'] = json_encode(['Message' => "Invalid Input"]);
                    echo json_encode($response);
                    exit;
                }
            }
            $entityManager->flush();
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Mass Preventive Created"]);
        } else {
            echo header("HTTP/1.1 400 Bad Request");
            echo json_encode(['Message' => "Invalid Input"]);
        }
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
