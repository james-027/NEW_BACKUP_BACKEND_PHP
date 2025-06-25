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
        $searchTerm = isset($input['search']) ? trim($input['search']) : '';
        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('c')  
                ->from(configuration\category::class, 'c')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(c.description)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $categories = $queryBuilder->getQuery()->getResult(); 
            $category_list = [];
            foreach ($categories as $category) {
                $user_list = [];
                foreach($category->getCategoryuser() as $user){
                    array_push($user_list,[
                    'value' => $user->getId(),
                    'label' => $user->getStore() ? '( '.$user->getUsertype()->getDescription().' ) '.$user->getStore()->getOutletname() : '( '. $user->getUsertype()->getDescription(). ' ) '.($user->getFirstname(). ' '. $user->getLastname() ?: ''), 
                    ]);
                }
                array_push($category_list,[
                    'value' => $category->getId(),
                    'label' => $category->getDescription(),
                    'category_user' => $user_list,
                ]);
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($category_list);
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
