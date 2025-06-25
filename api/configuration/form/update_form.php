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
        $token = json_decode(getBearerToken(),true);
        $user =  $entityManager->find(configuration\user::class,$token['user_id']);
        $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
        $form->setTitle($input['title']);
        $form->setRemark($input['remarks']);
        $form->setFormtype($input['type_id']);
        $new_reform = new configuration_process\reform;
         $new_reform->setDescription("Updated by " .$user->getFirstname() ." " .  $user->getLastname());
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime('now', $timezone);
        $new_reform->setDateupdate($date);
        $new_reform->setUpdatedby($token['user_id']);
        $entityManager->persist($new_reform);
        $entityManager->flush();
        $form->setFormreform($new_reform);
        $entityManager->flush();

        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => $input['tittle']. " updated"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    