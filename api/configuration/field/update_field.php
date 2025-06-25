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
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){  
                $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                $type = $entityManager->find(configuration_process\field_type::class,$input['type_id']);
                $field->setFieldtype($type->getId());
                $field->setRowoccupied($input['row_occupied']);
                $field->setColoccupied($input['col_occupied']);
                $field->setStyle($input['style']);
                $field->setQuestion($input['question']);
                $field->setFormula($input['formula']);
                $select_formula = json_decode($input['formula'], true);
                $field->setAnswer($select_formula['answer']);
                $field->setRadio($input['radio']);
                $field->setActivatestyle($input['activate_style']);
                $entityManager->flush();
                echo header("HTTP/1.1 200 OK");
                echo json_encode(['Message' => "Field updated"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    