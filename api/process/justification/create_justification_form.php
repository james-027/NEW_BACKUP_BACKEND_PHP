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
        $token = json_decode(getBearerToken(),true);
        $database = json_decode(getBearerToken(),true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $entityManager = $dbConnection->getEntityManager();
        $form = $entityManager->find(configuration_process\form::class, $input['form_id']);
     
        $repository = $entityManager->getRepository(process\user::class);
        $existingUser = $repository->findOneBy(['id' => $token['user_id']]);

        if($existingUser){
            $user = $existingUser;
        }else{
            $user = new process\user;
            $user->setId($token['user_id']);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        $justification_form = new configuration_process\justification_form;
        $justification_form->setCreatedby($user);
        $justification_form->setDescription($input['description']);
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime('now', $timezone);
        $justification_form->setDatecreated($date);
        $entityManager->persist($justification_form);
        $form->setJustificationForm($justification_form);
        $entityManager->flush();
        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => "Justification Created" ]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }