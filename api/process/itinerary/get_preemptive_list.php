<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$input = (array)json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) { 
            $token = json_decode(getBearerToken(), true);
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();    
            $searchTerm = isset($input['search']) ? trim($input['search']) : '';
        try {
            $queryBuilder = $processDb->createQueryBuilder();
            $queryBuilder->select('p')  
                ->from(configuration_process\preemptive::class, 'p')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(p.user_id)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $preemptives = $queryBuilder->getQuery()->getResult(); 
            $preemptive_list = [];
            foreach ($preemptives as $preemptive) {
                $user = $entityManager->find(configuration\user::class,$preemptive->getUser());
                $store = $entityManager->find(configuration\store::class,$preemptive->getStore());
                array_push($preemptive_list,[
                    'id' => $preemptive->getId(),
                    'user' => $user->getFirstname() . " ". $user->getLastname(),
                    'store' => $store->getOutletname(),
                    'date_created' => $preemptive->getDateCreated()->format('Y-m-d'),
                    'date_planned' => $preemptive->getDateplanned()->format('Y-m-d'),
                    'itinerary' => $preemptive->getItinerary(),
                    'date_actual' => $preemptive->getDateactual()->format('Y-m-d'),
                    'remark' => $preemptive->getRemark(),
                    'created_by' => $preemptive->getCreatedBy(),

                ]);
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($preemptive_list);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
?>
