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
        $userRepository = $entityManager->getRepository(configuration\user::class);
        $existingUser = $userRepository->findOneBy(['username' => $input['user_name']]);
        if($existingUser){
            if ($existingUser->getId()===$input['user_id']){
            $user = $entityManager->find(configuration\user::class,$input['user_id']);
            if($input['picture']!=""){
                $user->setPicture($input['picture']);
                }
            if($input['password']!=""){
            $user->setPassword($input['password']);
            }
            if($input['user_type']!=""){
                $user_type = $entityManager->find(configuration_process\user_type::class,$input['user_type']);
                $user->setUsertype($user_type);
            }
            $user->setActivate($input['activate']);
            $user->setUsername($input['user_name']);
            $user->setEmployeeNumber($input['employee_number']);
            $user->setFirstname($input['first_name']);
            $user->setMiddlename($input['middle_name']);
            $user->setLastname($input['last_name']);
            $user->setEmail($input['email']);
            $entityManager->persist($user);
            $entityManager->flush();    
            header('HTTP/1.1 200 OK');
            echo json_encode(["Message"=>"User Updated"]);
            }
            else{
                echo header("HTTP/1.1 409 Conflict");
                echo json_encode(['Message' => 'Username Already Exists']);
            } 
            }else{
            $user = $entityManager->find(configuration\user::class,$input['user_id']);
                      
                        if($input['password']!=""){
                        $user->setPassword($input['password']);
                        }
                        if($input['user_type']!=""){
                            $user_type = $entityManager->find(configuration_process\user_type::class,$input['user_type']);
                            $user->setUsertype($user_type);
                        }
                        $user->setActivate($input['activate']);
                        $user->setUsername($input['user_name']);
                        $user->setEmployeeNumber($input['employee_number']);
                        $user->setFirstname($input['first_name']);
                        $user->setMiddlename($input['middle_name']);
                        $user->setLastname($input['last_name']);
                        $user->setEmail($input['email']);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        echo header("HTTP/1.1 200 OK");
                        echo json_encode(['Message' => "User Updated"]);
    }
         
      

    }

}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
