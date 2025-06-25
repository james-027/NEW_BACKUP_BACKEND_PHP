<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Headers:Content-Type, Authorization");
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
    $bearerToken = getBearerToken(); 

    $origin = new configuration\origin;

    if(getBearerToken()){
        $token = json_decode(getBearerToken(),true);

        $user_id = $token['user_id'];
        
        $user = $entityManager->find(configuration\user::class,$user_id);
      
        header('HTTP/1.1 200 OK'); 
        echo json_encode(
            [
             "first_name"=>$user->getFirstname(),
             "last_name"=>$user->getLastname(),
             "picture"=> $origin->getOrigin($user->getPath()->getDescription(),$user->getPicture()),
             "user_type"=>$user->getUsertype()->getDescription()  
            ]
        );
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}






?>
