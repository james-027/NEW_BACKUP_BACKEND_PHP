<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];  

$full_domain = $protocol."://" .$host."/digital_workspace/api/byte/get_icon.php";

require_once __DIR__ . '/../../../database.php'; 


$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $origin = new configuration\origin;

    $meta = $entityManager->getRepository(configuration\meta::class)->findAll()[0];
    
    header('HTTP/1.1 200 OK');
    echo json_encode(
        [
        "title"=>$meta->getTitle(),
        "icon"=>$origin->getOrigin($meta->getPath()->getDescription(),$meta->getIcon()),
        "path"=>$meta->getPath()->getDescription(),
        "theme_color"=>$meta->getThemecolor()
        ]
);

}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}