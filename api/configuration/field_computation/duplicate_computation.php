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
$databaseName2 = "dws_db_2025";
$dbConnection = new DatabaseConnection($databaseName2);
$processDb = $dbConnection->getEntityManager(); 
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){ 
            function sortById($a, $b) {
                return  $a['series'] - $b['series'];
            }
        $field_loop = new field_loop();
        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
        $new_task = new configuration_process\task;
        $new_task->setTitle($task->getTitle());
        $new_task->setDescription($task->getDescription());
        $new_task->setStatus($task->getStatus());
        $new_task->setStyle($task->getStyle());
        $new_task->setRowset($task->getRowset());
        $new_task->setColset($task->getColset());
        // $new_task->setSeries(count($form->getFormtask()));
        $entityManager->persist($new_task);
        $entityManager->flush();
        foreach($task->getTaskvalidation() as $validation){
        $new_validation = new configuration_process\validation();
        $new_validation->setValid($validation->getValid() ? $validation->getValid() : null);
        $new_validation->setCreatedby($validation->getCreatedby());
        $new_validation->setUsertype( $validation->getUsertype());
        $entityManager->persist($new_validation);
        $entityManager->flush();
        $new_task->setTaskvalidation($new_validation);
        $entityManager->flush();
        }
        $entityManager->flush();
        foreach($task->getTaskfield() as $field){
            $setFieldLoop = $field_loop->setLoopfield($entityManager,$processDb,$field,$new_task,true);
        }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($new_task->getId());
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }