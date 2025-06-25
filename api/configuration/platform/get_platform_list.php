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
        $origin = new configuration\origin;
  
        $searchTerm = isset($input['search']) ? trim($input['search']) : '';

        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('p')  
                ->from(configuration_process\platform::class, 'p')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(p.description)', ':search'),
                
                ))
            
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $platforms = $queryBuilder->getQuery()->getResult(); 
            $platform_list = [];
            foreach ($platforms as $platform) {
                $path = $entityManager->find(configuration\path::class, $platform->getPath());
                array_push($platform_list,[
                    'id'=>$platform->getId(),
                    'description'=>$platform->getDescription(),
                    'picture'=>$origin->getOrigin($path->getDescription(),$platform->getIcon())
                ]);
            }
            
            header('HTTP/1.1 200 OK');
            echo json_encode($platform_list);
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

