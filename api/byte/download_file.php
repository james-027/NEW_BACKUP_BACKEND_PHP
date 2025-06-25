<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../../database.php'; 

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$parent_directory = dirname(__DIR__); 
$icon_directory = dirname($parent_directory);
$input = (array) json_decode(file_get_contents('php://input'), TRUE);


if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (getBearerToken()) {
$targetDirectory = realpath($icon_directory . "/../digital_workspace_file/icon/" . $input['path']);
$filePath = $targetDirectory . '/' . basename($input['filename']);

if ($targetDirectory && file_exists($filePath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));
    flush();
    readfile($filePath);
    exit;
} else {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(["Message" => "File not found."]);
    exit;
}
    } else {

        header('HTTP/1.1 401 Unauthorized'); 
        echo json_encode(["Message" => "Unauthorized access. Invalid or missing token."]);
    }

} else {

    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
