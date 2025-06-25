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
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){ 
            $searchTerm = isset($input['search']) ? trim($input['search']) : '';
        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('ut')  
                ->from(configuration_process\user_type::class, 'ut')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(ut.description)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $users = $queryBuilder->getQuery()->getResult(); 
            $userList = [];
        
            foreach ($users as $user) {
                    $platformList = [];
                    $usertypeList = [];

                foreach($user->getUsertypeplatform() as $platform){                
                    array_push($platformList,[
                    'value' => $platform->getId(),
                    'label' => $platform->getDescription()
                    
                ]);
                }
                    foreach($user->getUsertypeitinerarytype() as $user_type){                
                    array_push($usertypeList,[
                    'value' => $user_type->getId(),
                    'label' => $user_type->getDescription()
                    
                ]);
                }
                $userList[] = [
                    'value'=>$user->getId(),
                    'label'=>$user->getDescription(),
                    'valid'=>false,
                    'platform_assign'=>$platformList,
                    'itinerary_type_assign'=>$usertypeList
                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($userList);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
        } 
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

    function check_lapse_date($dateTimeString) {
    $timezone = new DateTimeZone('Asia/Manila');
    $providedDateTime = new DateTime($dateTimeString, $timezone);
    $currentDateTime = new DateTime('now', $timezone);
    return $providedDateTime < $currentDateTime;
}