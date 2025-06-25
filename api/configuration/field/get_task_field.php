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
    $origin = new configuration\origin;
        if(getBearerToken()){ 
        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
                $field_list = [];
                foreach ($task->getTaskfield() as $field) {
                    $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
                    $path = $entityManager->find(configuration\path::class,$type->getPath());
                    array_push($field_list,[
                    'id'=>$field->getId(),
                    'field_type'=>$type->getDescription(),
                    'formula'=>$field->getFormula(),
                    'question'=>$field->getQuestion(),
                    'style'=>$field->getStyle(),
                    'row_occupied'=>$field->getRowoccupied(),
                    'col_occupied'=>$field->getColoccupied(),
                    'row_no'=>$field->getRowno(),
                    'col_no'=>$field->getColno(),
                    'radio'=>$field->getRadio(),
                    'status'=>"R",
                    'answer'=>$field->getAnswer(),
                    "activate_style"=>$field->getActivatestyle()

                ]);
                }
                function sortById($a, $b) {
                    return  $a['series'] - $b['series'];
                }
                usort($field_list, 'sortById'); 
                header('HTTP/1.1 200 OK');
                echo json_encode($field_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }