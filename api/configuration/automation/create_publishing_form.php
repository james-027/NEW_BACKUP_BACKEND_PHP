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
            $token = json_decode(getBearerToken(),true);
            $repository = $entityManager->getRepository(process\user::class);
            $existingUser = $repository->findOneBy(['id' => $token['user_id']]);
            if($existingUser){
                $new_form = new configuration_process\automation_form_publishing;
                $new_form->setForm($input['form_id']);
                $new_form->setCreatedby($token['user_id']);
                $new_form->setFormtype($input['type_id']);
                $new_form->setPriority($input['priority']);
                $new_form->setRemark($input['remark']);
                $timezone = new DateTimeZone('Asia/Manila');
                $date = new DateTime('now', $timezone);
                $new_form->setDatecreated($date);
                $new_form->setDatepublish($input['publish_date']);
                $entityManager->persist($new_form);
                $entityManager->flush();
            }
        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message"=>"Form Created"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }