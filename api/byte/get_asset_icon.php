<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers:Content-Type, Authorization");

require_once __DIR__ . '/../../database.php'; 

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$parent_directory = dirname(__DIR__);
$main_pickle_directory = dirname($parent_directory);
$input = (array) json_decode(file_get_contents('php://input'), TRUE);


if($_SERVER['REQUEST_METHOD']==="GET"){  

        $fileExtension = pathinfo($_GET['file'], PATHINFO_EXTENSION);
        $file = $main_pickle_directory ."/file/asset/". $_GET['file'];
        if (file_exists($file)) {
            if (in_array($fileExtension, ['png', 'jpg', 'jpeg', 'gif'])) {
                header('HTTP/1.1 200 OK');
                header("Content-Type: image/png");
                readfile($file);
            } 
        } else {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(["Message" => "File not found"]);
            }
}else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}


 