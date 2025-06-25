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
if($_SERVER['REQUEST_METHOD']==="POST"){
    $user_repository = $entityManager->getRepository(configuration\user::class);
    $user = $user_repository->findOneBy(['username' => $input['user_name']]);
   if ($user && $user->authenticate_user($input['password'])) {
        $tokens = new configuration\tokens;
        $tokens = $tokens->getToken($user->getId());
        if($user->getActivate()){
            header('HTTP/1.1 200 OK');
            echo json_encode([
                'user_type'=>$user->getUsertype()->getId(),
                'token' => $tokens
            ]);
        }else{
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["Message" => "Unauthorized, Invalid or Expired Token."]);
        }
    } else {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(["Message" => "Authentication failed"]);
    }
}else{
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}


