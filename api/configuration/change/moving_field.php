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
$check = false;

function CheckFieldUnderTask($field_id, $destination_id, $check) {
        global $entityManager;

      
        $field = $entityManager->find(configuration_process\field::class, $field_id);
   
        if (!$field) {
            return false; 
        }
        foreach ($field->getFieldlink() as $link) {
            if ($link->getId() === $destination_id) {
                $check = true; 
                return true;    
            }
            if (CheckFieldUnderTask($link->getId(), $destination_id, $check)) {
                return true;   
            }
        }
        return false; 
}


if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){   
            if($input['identifier']==="outer"){
                if($input['determine']==="field"){
                    //MOVING LINKED FIELD TO TASK
                    $origin = $entityManager->find(configuration_process\field::class,$input['origin_id']);
                    $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                    $origin->setAllfieldlink($origin->removeFieldlink($origin->getFieldlink(),$field));  
                    $entityManager->flush();   
                    $destination = $entityManager->find(configuration_process\task::class,$input['destination_id']);  
                    $field->setSeries(count($destination->getTaskfield())); 
                    $destination->setTaskfield($field);    
                    $entityManager->flush();  

                    $my_task_field= $entityManager->find(configuration_process\task::class,$input['destination_id']);
                    $my_task_field_list = []; 
                    $field_links = $my_task_field->getTaskfield();
                    foreach ($field_links as $field_link) { 
                        array_push($my_task_field_list,['series'=>$field_link->getSeries(),'id'=>$field_link->getId()]);
                    }
                    function sortById($a, $b) {
                        return  $a['series'] - $b['series'];
                    }
                        usort($my_task_field_list, 'sortById');
                    foreach ($my_task_field_list  as $index => $series) {
                       $change_series = $entityManager->find(configuration_process\field::class,$series['id']);
                       $change_series->setSeries($index);
                       $entityManager->flush();
                       }
                    }
                    else if($input['determine']==="task"){
                        $task = $entityManager->find(configuration_process\task::class,$input['origin_id']);
                        $field = $entityManager->find(configuration_process\field::class,$input['field_id']);  
                        $field->setSeries(count($task->getTaskfield())); 
                        $entityManager->flush();    
                        $task_field = []; 
                        foreach ($task->getTaskfield() as $field) { 
                            array_push($task_field,['series'=>$field->getSeries(),'id'=>$field->getId()]);
                        }
                        function sortById($a, $b) {
                            return  $a['series'] - $b['series'];
                        }
                            usort($task_field, 'sortById');
                        foreach ($task_field  as $index => $series) {
                            $change_series = $entityManager->find(configuration_process\field::class,$series['id']);
                            $change_series->setSeries($index);
                            $entityManager->flush();
                        }
                }
            
            }else if($input['identifier']==="task"){
                //MOVE FIELD TO FIELD
                if(CheckFieldUnderTask($input['field_id'],$input['destination_id'],$check)===false){   
                    $task = $entityManager->find(configuration_process\task::class,$input['origin_id']);; 
                    $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                    $task->setAlltaskfield($task->removeTaskfield($task->getTaskfield(),$field)); 
                    $entityManager->flush();        
                    $destination = $entityManager->find(configuration_process\field::class,$input['destination_id']);  
                    $field->setSeries(count($destination->getFieldlink()));  
                    $destination->setFieldlink($field);   
                    $entityManager->flush();  

                    $field_list = []; 
                    foreach ($destination->getFieldlink() as $link) {
                       array_push($field_list,['series'=>$link->getSeries(),'id'=>$link->getId()]);
                       }
                       
                    function sortById($a, $b) {
                        return  $a['series'] - $b['series'];
                    }
                        usort($field_list, 'sortById'); 
                    foreach ($field_list  as $index => $series) {
                       $change_series = $entityManager->find(configuration_process\field::class,$series['id']);
                       $change_series->setSeries($index);
                       $entityManager->flush();
                    }
                } 
            }else{
                if(CheckFieldUnderTask($input['field_id'],$input['destination_id'],$check)===false){     
                $origin = $entityManager->find(configuration_process\field::class,$input['origin_id']);
                $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                $origin->setAllfieldlink($origin->removeFieldlink($origin->getFieldlink(),$field));  
                $entityManager->flush();       
                $destination = $entityManager->find(configuration_process\field::class,$input['destination_id']);  
                $field->setSeries(count($destination->getFieldlink()));  
                $destination->setLink($field);   
                $entityManager->flush();  
                $field_list = []; 
                    foreach ($destination->getFieldlink() as $link) {
                        array_push($field_list,['series'=>$link->getSeries(),'id'=>$link->getId()]);
                    }
                function sortById($a, $b) {
                    return  $a['series'] - $b['series'];
                }
                    usort($field_list, 'sortById');
                foreach ($field_list  as $index => $series) {
                $change_series = $entityManager->find(configuration_process\field::class,$series['id']);
                $change_series->setSeries($index);
                $entityManager->flush();
                }
                }
            }
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Field Moved Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }