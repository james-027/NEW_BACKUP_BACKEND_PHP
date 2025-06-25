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
        $title = $input['title'] ? $input['title'] : "Untitled Form";
        $token = json_decode(getBearerToken(),true);
        $new_form = new configuration_process\form;
        $table_form = new configuration_process\table_form;
        $new_form->setTitle($title);
        $new_form->setRemark($input['remarks']);
        $new_form->setCreatedby($token['user_id']);
        $new_form->setVersion('0.0.0');
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime('now', $timezone);
        $new_form->setDatecreated($date);
        $new_form->setFormtype($input['type_id']);
        $new_form->setChance('1.1.1');
        $entityManager->persist($new_form);
        $entityManager->flush();
        $table_form->setForm($new_form);
        $entityManager->persist($table_form);
        $entityManager->flush();
        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => $title." Created" ]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
