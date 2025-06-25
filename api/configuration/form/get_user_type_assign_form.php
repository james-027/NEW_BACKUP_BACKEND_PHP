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
                    $formList = [];

                foreach($user->getUsertypeform() as $form){
                $publish_form = $entityManager->find(configuration_process\form::class,$form->getId());
                $version_count = count($publish_form->getFormlink());
                $date_to_check = new \DateTime();
                $last_version = null;
                for ($i = $version_count - 1; $i >= 0; $i--) {
                    if (check_lapse_date($publish_form->getFormlink()[$i]->getDatecreated()->format('d-m-Y g:i:s A'))) {
                        $last_version = $publish_form->getFormlink()[$i];
                    break;
                    }
                }
                
                    array_push($formList,[
                    'value' => $form->getId(),
                    'label' => $form->getTitle()." ".$last_version->getVersion()." "."( ". $last_version->getDatecreated()->format('F j, Y')." )"
                    
                ]);
                }
                
                $userList[] = [
                    'value'=>$user->getId(),
                    'label'=>$user->getDescription(),
                    'valid'=>false,
                    'form_assign'=>$formList
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