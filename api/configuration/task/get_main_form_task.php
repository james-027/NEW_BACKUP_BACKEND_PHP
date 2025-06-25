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
        $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
        $task_list = [];
        foreach($form->getFormtask() as $task){
            $validation_list = [];
            foreach($task->getTaskvalidation() as $validation){
                $user_profile = $entityManager->find(configuration_process\user_type::class, $validation->getUsertype());
                array_push($validation_list,['id'=>$validation->getId(),"validator"=>$user_profile->getDescription() ? $user_profile->getDescription(): "","valid"=>$validation->getValid()]);
            }
            $status = $entityManager->find(configuration_process\status::class,$task->getStatus());
            array_push($task_list,['id'=>$task->getId(),'title'=>$task->getTitle(),'description'=>$task->getDescription(),'status'=>$status->getDescription(),'series'=>$task->getSeries(),'validation'=>$validation_list,
        "field"=>count($task->getTaskfield()),
        'style'=>$task->getStyle(),
        'row_set'=>$task->getRowset(),
        'col_set'=>$task->getColset(),
    ]);
        }
        function sortById($a, $b) {
            return  $a['series'] - $b['series'];
        }
        
       usort($task_list, 'sortById'); 

        echo header("HTTP/1.1 200 OK");
        echo json_encode($task_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }



    