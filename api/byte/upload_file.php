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

        $response = array();
        $targetDirectory = $icon_directory . "/../digital_workspace_file/icon/" . $_POST['path'] ;
        $targetDirectory = realpath($targetDirectory);
        if (isset($_FILES["file"])) {
            $target_file = $targetDirectory . '/'.basename($_FILES["file"]['name']);
                if (file_exists($target_file)) {
                    if (unlink($target_file)) {
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                            header('HTTP/1.1 200 OK');
                            echo json_encode(["Message" => "The file " . basename($_FILES["file"]['name']) . " has been updated."]);
                        } else {
                            header('HTTP/1.1 400 Bad Request');
                            echo json_encode(["Message" => "Sorry, there was an error uploading your file."]);
                        }
                    } else {
                        header('HTTP/1.1 400 Bad Request');
                        echo json_encode(["Message" => "Sorry, we could not delete the existing file."]);
                    }
                } else {
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                        header('HTTP/1.1 200 OK');
                        echo json_encode(["Message" => "The file " . basename($_FILES["file"]['name']) . " has been uploaded."]);
                    } else {
                        header('HTTP/1.1 400 Bad Request');
                        echo json_encode(["Message" => "Sorry, there was an error uploading your file."]);
                    }
                }
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(["Message" => "No file selected for upload."]);
        }

    } else {

        header('HTTP/1.1 401 Unauthorized'); 
        echo json_encode(["Message" => "Unauthorized access. Invalid or missing token."]);
    }

} else {

    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
