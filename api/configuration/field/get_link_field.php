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
        $origin = new configuration\origin;
        $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
        $link_list = [];
        if($field){
            foreach($field->getFieldlink() as $link){
                $type = $entityManager->find(configuration_process\field_type::class,$link->getFieldtype());
                $path = $entityManager->find(configuration\path::class,$type->getPath());
            array_push($link_list,['id'=>$link->getId(),'type'=>$type->getDescription(),
            'picture'=>$origin->getOrigin($path->getDescription(),$type->getIcon()),'answer'=>$link->getAnswer(),'formula'=>$link->getFormula(),'question'=>$link->getQuestion(),
            "series"=>$link->getSeries()
        ]);
        } 
        }
        
        function sortById($a, $b) {
            return  $a['series'] - $b['series'];
        }
        
       usort($link_list, 'sortById'); 

        echo header("HTTP/1.1 200 OK");
        echo json_encode($link_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }