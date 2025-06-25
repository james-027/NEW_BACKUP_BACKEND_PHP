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
            $userRepository = $entityManager->getRepository(configuration\user::class);
            $existingUser = $userRepository->findOneBy(['username' => $input['user_name']]);
            if(!$existingUser){
                $new_user = new configuration\user;
                $new_user->setUsername($input['user_name']);
                $new_user->setPassword($input['password']);
                $new_user->setFirstname($input['first_name']);
                $new_user->setMiddlename($input['middle_name']);
                $new_user->setLastname($input['last_name']);
                $new_user->setEmail($input['email']);
                $path = $entityManager->find(configuration\path::class,5);
                $new_user->setPath($path);
                $new_user->setEmployeenumber($input['employee_number']);
                $user_type = $entityManager->find(configuration_process\user_type::class,$input['user_type']);
                $new_user->setUsertype($user_type);
                $new_user->setActivate(true);
                $timezone = new DateTimeZone('Asia/Manila');
                $date = new DateTime('now', $timezone);
                $new_user->setDatecreated($date);
                $new_user->setPicture($input['picture'] ? $input['picture'] : "profile.png");
                $entityManager->persist($new_user);
                $entityManager->flush();
                header('HTTP/1.1 201 OK');
                echo json_encode(["Message"=>"Successfully Created!"]);
                
            }else{
                header('HTTP/1.1 409 Conflict'); 
                echo json_encode(["Message"=>"Username already exist"]);
            }
            
        }       
}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
?>

