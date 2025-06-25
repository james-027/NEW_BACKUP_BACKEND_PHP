<?php
// Enable error reporting for debugging
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
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){    
      

          $searchTerm = isset($input['search']) ? trim($input['search']) : '';
    
        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('it')  
                ->from(configuration_process\itinerary_type::class, 'it')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(it.description)', ':search'),
                
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $itinerary_type = $queryBuilder->getQuery()->getResult(); 
            $type_list = [];
            foreach ($itinerary_type as $list) {
                $type_list[] = [
                    'value' => $list->getId(),
                    'label' => $list->getDescription(),
                ];
            }
            
        
            header('HTTP/1.1 200 OK');
            echo json_encode($type_list);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['Message' => 'An Message occurred: ' . $e->getMessage()]);
        }

    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

}

