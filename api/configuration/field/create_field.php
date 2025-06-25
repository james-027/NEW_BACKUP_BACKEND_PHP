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
        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
        $field = new configuration_process\field;
        $type = $entityManager->find(configuration_process\field_type::class,$input['type_id']);
        $field->setFieldtype($type->getId());
        $field->setRowoccupied($input['row_occupied']);
        $field->setColoccupied($input['col_occupied']);
        $field->setRowno($input['row_no']);
        $field->setColno($input['col_no']);
        $field->setStyle($input['style']);
        $field->setFormula($input['formula']);
        $field->setActivatestyle($input['activate_style']);
        $entityManager->persist($field);
        $entityManager->flush();
        $task->setTaskfield($field);
        $entityManager->flush();
        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => "Field created"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }