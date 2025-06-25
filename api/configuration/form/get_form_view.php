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
        $type = $entityManager->find(configuration_process\form_type::class,$form->getFormtype());
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['id'=>$form->getId(),'title'=>$form->getTitle(),"type_id"=>$type->getId(), 'version'=>$form->getVersion(),'remarks'=>$form->getRemark()
    ]);
        } 
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }