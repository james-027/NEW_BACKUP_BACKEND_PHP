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
        $token = json_decode(getBearerToken(),true);
        $store = new configuration\store;
        $store->setOutletifs($input['outlet_ifs']);
        $store->setOutletcode($input['outlet_code']);
        $store->setTown($input['town']);
        $store->setZipcode($input['zip_code']);
        $store->setOutletname($input['outlet_name']);
        $store->setAddress($input['address']);
        $store->setLatitude($input['latitude']);
        $store->setLongitude($input['longitude']);
        $store->setDistance($input['distance']);
        $user = $entityManager->find(configuration\user::class,$token['user_id']);
        $store->setCreatedby($user);
        $entityManager->persist($store);
        $entityManager->flush();
        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => "Store created"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }