<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Headers:Content-Type, Authorization");
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
    $bearerToken = getBearerToken(); 

    $origin = new configuration\origin;

    if(getBearerToken()){
        $token = json_decode(getBearerToken(),true);
        $identifier = $input['identifier'];
        $user_id = null;
            if($identifier){
                $user_id = $input['user_id'];
            }else{
                $user_id = $token['user_id'];
            }
            
        $user = $entityManager->find(configuration\user::class, $user_id); 
        $user_list = [];
        foreach($user->getUserassign() as $assign){
         array_push($user_list, [
                    'value' => $assign->getId(),
                    'label' => $assign->getStore() ? $assign->getStore()->getOutletname() : ($assign->getFirstname() ?: ''), 
         ]);
        }
        header('HTTP/1.1 200 OK'); 
        echo json_encode(
           $user_list
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
