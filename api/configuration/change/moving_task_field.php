<?php

//CHANGE FIELD

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

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){   
            if($input['identifier']==="task"&&$input['drop_identifier']==="task"){   
                $field = $entityManager->find(configuration_process\field::class,$input['field_id']);  
                $field->setSeries($input['series']-1);       
                $entityManager->flush();       
                $task= $entityManager->find(configuration_process\task::class,$input['origin_id']);
                $field_list = [];  
                foreach ($task->getTaskfield() as $field) {
                    array_push($field_list,['series'=>$field->getSeries(),'id'=>$field->getId()]);
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
            else if($input['identifier']==="task"&&$input['drop_identifier']==="field"){ 
                $origin = $entityManager->find(configuration_process\task::class,$input['origin_id']);
                $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                $origin->setAlltaskfield($origin->removeTaskfield($origin->getTaskfield(),$field));  
                $entityManager->flush();      
                $destination = $entityManager->find(configuration_process\field::class,$input['destination_id']);  
                $field->setSeries($input['series']-1);  
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
        else if($input['identifier']==="field"&&$input['drop_identifier']==="field"){ 
            $origin = $entityManager->find(configuration_process\field::class,$input['origin_id']);
            $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
            $origin->setAllfieldlink($origin->removeFieldlink($origin->getFieldlink(),$field));  
            $entityManager->flush();     
            $destination = $entityManager->find(configuration_process\field::class,$input['destination_id']);  
            $field->setSeries($input['series']-1);   
            $destination->setFieldlink($field);    
            $entityManager->flush();   
            $destination = $entityManager->find(configuration_process\field::class,$input['destination_id']);  
            $field->setSeries($input['series']-1);  
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
    else if($input['identifier']==="field"&&$input['drop_identifier']==="task"){ 
        $origin = $entityManager->find(configuration_process\field::class,$input['origin_id']);
        $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
        $origin->setAllfieldlink($origin->removeFieldlink($origin->getFieldlink(),$field));  
        $entityManager->flush();      

        $destination = $entityManager->find(configuration_process\task::class,$input['destination_id']);  
        $field->setSeries($input['series']-1);   
        $destination->setTaskfield($field);    
        $entityManager->flush();   
        $field_list = []; 
            foreach ($destination->getTaskfield() as $link) {
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
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Field Moved Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }