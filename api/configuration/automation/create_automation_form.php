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
                $new_form = new configuration_process\automation_form;
                $new_form->setForm($input['form_id']);
                $new_form->setCreatedby($token['user_id']);
                $new_form->setItinerary($input['itinerary_id']);
                $entityManager->persist($new_form);
                $entityManager->flush();
            }else{
                // $user = new process\user;
                // $user->setId($token['user_id']);
                // $processDb->persist($user);
                // $processDb->flush();
                // $generated_form = $processDb->find(configuration_process\form::class, $form_loop);
                // $user->setUserformgenerator($generated_form);
                // $processDb->flush();
            }
        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message"=>"Form Completed"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }