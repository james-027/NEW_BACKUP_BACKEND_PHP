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

$input = (array)json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) { 
        $searchTerm = isset($input['search']) ? trim($input['search']) : '';
        if (empty($searchTerm)) {
            header('HTTP/1.1 200 OK');
            echo json_encode([]);
            exit;
        }
        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('s','u')  
                ->from(configuration\store::class, 's')  
                ->leftJoin('s.created_by', 'u') 
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(u.first_name)', ':search'),
                    $queryBuilder->expr()->like('LOWER(u.last_name)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.outlet_ifs)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.outlet_code)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.town)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.zip_code)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.outlet_name)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.address)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.latitude)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.longitude)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.distance)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.start_time)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.start_time)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.end_time)', ':search'),
                    $queryBuilder->expr()->like('LOWER(s.date_created)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $stores = $queryBuilder->getQuery()->getResult(); 
            $store_list = [];
            foreach ($stores as $store) {
                $store_list[] = [
                    'id' => $store->getId(),
                    'outlet_ifs' => $store->getOutletifs(),
                    'outlet_code' => $store->getOutletcode(),
                    'town' => $store->getTown(),
                    'zip_code' => $store->getZipcode(),
                    'outlet_name' => $store->getOutletname(),
                    'address' => $store->getAddress(),
                    'latitude' => $store->getLatitude(),
                    'longitude' => $store->getLongitude(),
                    'distance' => $store->getDistance(),
                    'created_by' => $store->getCreatedby()->getFirstname() . " " . $store->getCreatedby()->getLastname()
                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($store_list);
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

