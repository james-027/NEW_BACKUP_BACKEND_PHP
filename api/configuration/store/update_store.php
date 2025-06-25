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
    $bearerToken = getBearerToken(); 

    if(getBearerToken()){
        $store = $entityManager->find(configuration\store::class,$input['store_id']);
        if($input['outlet_ifs']!= ""){
            $store->setOutletifs($input['outlet_ifs']);
        }
        if($input['outlet_code']!= ""){
            $store->setOutletcode($input['outlet_ifs']);
        }
        if($input['town']!= ""){
            $store->setTown($input['town']);
        }
        if($input['zip_code']!= ""){
            $store->setZipcode($input['zip_code']);
        }
        if($input['outlet_name']!= ""){
            $store->setOutletname($input['outlet_name']);
        }     if($input['address']!= ""){
            $store->setAddress($input['address']);
        }     if($input['latitude']!= ""){
            $store->setLatitude($input['latitude']);
        }     if($input['longitude']!= ""){
            $store->setLongitude($input['longitude']);
        }     if($input['distance']!= ""){
            $store->setDistance($input['distance']);
        }

        $entityManager->flush();

        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Store Updated"]);




    
    }

}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
