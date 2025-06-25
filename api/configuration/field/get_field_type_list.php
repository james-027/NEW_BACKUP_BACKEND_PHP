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
            $queryBuilder->select('ft')  
                ->from(configuration_process\field_type::class, 'ft')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(ft.description)', ':search'), 
                    $queryBuilder->expr()->like('LOWER(ft.label)', ':search'), 
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $field_types = $queryBuilder->getQuery()->getResult(); 
            $field_type_list = [];
            foreach ($field_types as $field_type) {
                $path = $entityManager->find(configuration\path::class,$field_type->getPath());
                if ($field_type->getId() !== 2 && $field_type->getId() !== 3) {
                    $field_type_list[] = [
                        'id' => $field_type->getId(),
                        'description' => $field_type->getDescription(),
                        'picture' => $origin->getOrigin($path->getDescription(), $field_type->getIcon()),
                        'label' => $field_type->getLabel()
                    ];
                }
                 

            }
            header('HTTP/1.1 200 OK');
            echo json_encode($field_type_list);
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

