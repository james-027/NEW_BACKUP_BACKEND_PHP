<?php
ini_set('display_Messages', 1);
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
        try {

            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('u','ut','us')  
                ->from(configuration\user::class, 'u')  
                ->leftJoin('u.type_id', 'ut') 
                ->leftJoin('u.store_id', 'us') 
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(u.first_name)', ':search'),
                    $queryBuilder->expr()->like('LOWER(u.username)', ':search'),
                    $queryBuilder->expr()->like('LOWER(u.last_name)', ':search'),
                    $queryBuilder->expr()->like('LOWER(ut.description)', ':search'),
                    $queryBuilder->expr()->like('LOWER(us.outlet_name)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $users = $queryBuilder->getQuery()->getResult(); 
            $userList = [];
            foreach ($users as $user) {
                $userList[] = [
                    'value' => $user->getId(),
                    'label' => $user->getStore() ? '( '.$user->getUsertype()->getDescription().' ) '.$user->getStore()->getOutletname() : '( '. $user->getUsertype()->getDescription(). ' ) '.($user->getFirstname(). ' '. $user->getLastname() ?: ''), 
                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($userList);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['Message' => 'An Message occurred: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}



?>

