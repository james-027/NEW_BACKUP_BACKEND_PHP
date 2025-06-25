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
            $created = true;
            $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
            if($task->getRowset()<$input['row_occupied']){
                $created = false;
                }else if($task->getRowset()-$input['row_no']+1<$input['row_occupied']){
                $created = false;
                }
            foreach($task->getTaskfield() as $field){
        if($field->getRowno()===$input['row_no']){
            $created = false;
            }
        else{   
                if($field->getRowno() > $input['row_no']){
                    if($field->getRowno()-$input['row_no']<$input['row_occupied']){
                    $created = false;
                    }
                    }else{
                        if($field->getRowoccupied() ===$input['row_no']){
                            $created = false;
                        }else{
                                if($field->getRowoccupied() >$input['row_no']){
                                        $created = false;
                                }else{
                                    if($task->getRowset() <$input['row_occupied']){
                                        $created = false;
                                    }else{
                                        if($task->getRowset()-$input['row_no']+1<$input['row_occupied']){
                                        $created = false;}
                                    }
                                }
                    }
                }
            }
        }
        if($created){
                header('HTTP/1.1 201 Created');
                echo json_encode(['Message'=>"Block set!"]);
        }else{
            header('HTTP/1.1 409 Conflict');
            echo json_encode(["Message" => "Cannot occupied more than ".$input['row_occupied']."!"]);
        }
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }