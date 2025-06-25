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
            try{
                $token = json_decode(getBearerToken(),true);
                $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
                $taskCount = count( $form->getFormtask()); 
                $task = new configuration_process\task();
                $task->setTitle($input['title']);
                $task->setStyle($input['style']);
                $task->setRowset($input['row_set']);
                $task->setColset($input['col_set']);
                $task->setDescription($input['description']);
                $task->setStatus(1);
                $task->setSeries(0);
                $user_validations =  $input['user_type_validation']; 
                foreach($user_validations as $user_type){
                    $validation = new configuration_process\validation;            
                    $validation->setCreatedby($token['user_id']);
                    $validation->setUsertype( $user_type);
                    $validation->setValid(true);
                    $entityManager->persist($validation);
                    $entityManager->flush();
                    $task->setTaskvalidation($validation);
                }
                $user_assigns =  $input['user_type_assign']; 
                foreach($user_assigns as $user_assign){
                    $assign = new configuration_process\assign;            
                    $assign->setCreatedby($token['user_id']);
                    $assign->setUsertype( $user_assign);
                    $assign->setValid(true);
                    $entityManager->persist($assign);
                    $entityManager->flush();
                    $task->setTaskassign($assign);
                }
                $entityManager->persist($task);
                $form->setFormtask($task);
                $entityManager->flush(); 

            }catch(Exception $e){
                echo json_encode(["Message"=>$e->getMessage()]);
            }


        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => $input['title']. " created"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }