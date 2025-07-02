<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Headers:Content-Type, Authorization");
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
    if (getBearerToken()) {
        $token = json_decode(getBearerToken(), true);
        $database = $token['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $preemptive = $processDb->find(configuration_process\preventive::class, $input['preemptive_id']);
        $user = $entityManager->find(configuration\user::class, $preemptive->getUser());
        $created_by = $entityManager->find(configuration\user::class, $preemptive->getCreatedby());
        $store = $entityManager->find(configuration\store::class, $preemptive->getStore());
        header('HTTP/1.1 200 OK');
        echo json_encode([
            'id' => $preemptive->getId(),
            'user' => $user->getFirstname() . ' ' . $user->getLastname(),
            'store' => $store->getOutletname(),
            'itinerary' => $preemptive->getItinerary(),
            'date_created' => $preemptive->getDatecreated()->format('Y-m-d'),
            'date_planned' => $preemptive->getDateplanned()->format('Y-m-d'),
            'date_actual' => $preemptive->getDateactual()->format('Y-m-d'),
            'remark' => $preemptive->getRemark(),
            'created_by' => $created_by->getFirstname() . ' ' . $created_by->getLastname(),
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
