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
    $origin = new configuration\origin;
    if(getBearerToken()){
        
        $user = $entityManager->find(configuration\user::class,$input['user_id']);
        header('HTTP/1.1 200 OK');
        echo json_encode([
            "id"=>$user->getId(),
            'user_name'=>$user->getUsername(),
            "employee_number"=>$user->getEmployeenumber() ? $user->getEmployeenumber():"",
            "first_name"=>$user->getFirstname(),
            "last_name"=>$user->getLastname(),
            "activate"=>$user->getActivate() ? $user->getActivate():"",
            "email"=>$user->getEmail(),
            "store_id"=>$user->getStore() ? $user->getStore()->getId() : null,
            "user_type_id"=>$user->getUsertype()? $user->getUsertype()->getId(): null,
            //'picture' => $origin->getOrigin($user->getPath()->getDescription(), $user->getPicture() ? $user->getPicture() : "profile.png"),
            "user_type"=>$user->getUsertype()? $user->getUsertype()->getDescription(): "",
            "store"=>$user->getStore() ? $user->getStore()->getOutletname() : "",
            "confirm_password"=>"",
            "password"=>""
        ]);
    }
}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}