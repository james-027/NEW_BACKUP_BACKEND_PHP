<?php
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
    if (getBearerToken()) {
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $entityManager = $dbConnection->getEntityManager();
        $new_preemptive = $entityManager->find(configuration_process\preemptive::class, $input['preemptive_id']);
        $new_preemptive->setRemove(true);
        $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Successfully Updated"]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
