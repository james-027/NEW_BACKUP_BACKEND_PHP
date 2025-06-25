<?php
// Enable error reporting for debugging
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
    
    $origin = new configuration\origin;

    if(getBearerToken()){
        $token = json_decode(getBearerToken(),true);
        $user_id = $token['user_id'];
        
        $platform_list = [];
        $user = $entityManager->find(configuration\user::class, $user_id);
        
        foreach($user->getUsertype()->getUsertypeplatform() as $platform){
            $path = $entityManager->find(configuration\path::class, $platform->getPath());
            array_push($platform_list,[
                'id'=>$platform->getId(),
                'description'=>$platform->getDescription(),
                'picture'=>$origin->getOrigin($path->getDescription(),$platform->getIcon())
            ]);
        }
        echo header("HTTP/1.1 201 Created");
        echo json_encode($platform_list);
    }
}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}