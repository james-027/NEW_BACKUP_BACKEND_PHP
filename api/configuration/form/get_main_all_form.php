<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager(); 

$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "GET") {
        if(getBearerToken()){ 
        $forms = $entityManager->getRepository(configuration_process\form::class)->findAll();
        $form_list = [];
        foreach($forms as $form){
            $user = $entityManager->find(configuration\user::class,$form->getCreatedby());
            $type = $entityManager->find(configuration_process\form_type::class,$form->getFormtype());
            array_push($form_list,['id'=>$form->getId(),'title'=>$form->getTitle(),"type"=>$type->getDescription(),
            "created_by"=>$user->getFirstname() . " " . $user->getLastname(),
            "date_created"=>$form->getDatecreated()->format('m-d-Y'),'version'=>$form->getVersion(),
        ]);
        } 
        echo header("HTTP/1.1 200 OK");
        echo json_encode($form_list);
        } 
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }



    


