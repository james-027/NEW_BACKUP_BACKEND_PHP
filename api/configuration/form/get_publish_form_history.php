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
        $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
$formLinks = $form->getFormlink()->toArray(); 


usort($formLinks, function($a, $b) {
    return $b->getDatecreated() <=> $a->getDatecreated();
});

$form_list = [];

foreach ($formLinks as $link) {
    $user = $entityManager->find(configuration\user::class, $link->getCreatedby());
    $type = $entityManager->find(configuration_process\form_type::class, $link->getFormtype());

    $form_list[] = [
        'id' => $link->getId(),
        'title' => $link->getTitle(),
        'date_created' => $link->getDatecreated()->format('Y-m-d H:i:s'),
        'created_by' => $user->getFirstname(),
        'version' => $link->getVersion(),
        'form_type' => $type->getDescription(),
    ];
}

        echo header("HTTP/1.1 200 Ok");
        echo json_encode($form_list);
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


?>