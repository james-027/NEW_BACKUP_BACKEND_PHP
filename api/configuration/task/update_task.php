<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){    
        $token = json_decode(getBearerToken(),true);
        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
        $task->setTitle($input['title']);
        $task->setDescription($input['description']);
        $task->setStyle($input['style']);
        $task->setRowset($input['row_set']);
        $task->setColset($input['col_set']);
        $user_type_validations =  $input['user_type_validation'];
        $user_type_assigns =  $input['user_type_assign'];

        foreach($task->getTaskvalidation() as $validation){
            $task->removeTaskvalidation($task->getTaskvalidation(),$validation);
            $entityManager->remove($validation);
            $entityManager->flush();
        }
            foreach($user_type_validations as $user_type_validation){
            $validation = new configuration_process\validation;            
            $validation->setUsertype( $user_type_validation);
            $validation->setValid(true);
            $entityManager->persist($validation);
            $entityManager->flush();
            $task->setTaskvalidation($validation);
           
        }    
        
           foreach($task->getTaskassign() as $assign){
            $task->removeTaskassign($task->getTaskassign(),$assign);
            $entityManager->remove($assign);
            $entityManager->flush();
        }
            foreach($user_type_assigns as $user_type_assign){
            $assign = new configuration_process\assign;            
            $assign->setUsertype( $user_type_assign);
            $assign->setValid(true);
            $entityManager->persist($assign);
            $entityManager->flush();
            $task->setTaskassign($assign);
           
        }    


         $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Task updated"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    