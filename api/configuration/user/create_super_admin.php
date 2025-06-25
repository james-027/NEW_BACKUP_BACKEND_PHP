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
$allow = true;

if($_SERVER['REQUEST_METHOD']==="POST"){
    if($allow===true){
        $user_repository = $entityManager->getRepository(configuration\user::class);
        $existing_user = $user_repository->findOneBy(['username' => $input['user_name']]);
        if (!$existing_user) {
            $new_super_admin = new configuration\user;
            $new_super_admin->setEmail($input["email"]);
            $new_super_admin->setUsername($input["user_name"]);
            $new_super_admin->setPassword($input["password"]);
            $user_type = $entityManager->find(configuration_process\user_type::class,1);
            $path = $entityManager->find(configuration\path::class,5);
            $new_super_admin->setPath($path);
            $new_super_admin->setUsertype($user_type);
            $new_super_admin->setActivate(true);
            $entityManager->persist($new_super_admin);
            $entityManager->flush();
            header('HTTP/1.1 201 Created');
            echo json_encode(["Message"=>"New superadmin ".$input['email']." created!"]);
        } else {
            header('HTTP/1.1 409 Conflict');
            echo json_encode(["Message"=>"Username already exists"]);
        }
    }else{
        header('HTTP/1.1 409 Conflict');
        echo json_encode(["Message"=>"Registration deactivated"]);
    }
}else{
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}


