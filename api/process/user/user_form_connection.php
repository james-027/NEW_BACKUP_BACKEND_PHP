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
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();   
            $set_form = new form_loop();
            $table_form = new configuration_process\table_form;
            $form = $entityManager->find(configuration_process\form::class, $input['form_id']);
            $form_loop = $set_form->setFormloop($entityManager,$processDb,$form,false);
            $repository = $processDb->getRepository(process\user::class);
            $existingUser = $repository->findOneBy(['id' => $input['user_id']]);
            if($existingUser){
                $generated_form = $processDb->find(configuration_process\form::class, $form_loop);
                $existingUser->setUserformconnection($generated_form);
                $processDb->flush();
            }else{
                $user = new process\user;
                $user->setId($input['user_id']);
                $processDb->persist($user);
                $processDb->flush();
                $generated_form = $processDb->find(configuration_process\form::class, $form_loop);
                $user->setUserformconnection($generated_form);
                $processDb->flush();
            }
        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message"=>"User Form Connection Completed!"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }