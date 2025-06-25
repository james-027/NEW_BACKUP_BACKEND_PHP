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
        $response = [];
        $targetDirectory = $icon_directory . "/../digital_workspace_file/icon/" . $_POST['path'];
        $targetDirectory = realpath($targetDirectory);
        if (!is_dir($targetDirectory)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(["Message" => "Upload path does not exist."]);
            exit;
        }
        if (isset($_FILES["file"])) {
            $max_upload = $_POST['count']; 
            $file_size_mb = isset($_POST['size']) ? (int) $_POST['size'] : 5; 
            $maxFileSize = $file_size_mb * 1024 * 1024; 
            $fileCount = count($_FILES["file"]["name"]);
            $allSuccessful = true;

            if($fileCount > $max_upload){
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(["Message" => "You can only upload a maximum of $max_upload files."]);
                exit;
            }else{
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = basename($_FILES["file"]["name"][$i]);
                $tmpFile = $_FILES["file"]["tmp_name"][$i];
                $fileSize = $_FILES["file"]["size"][$i];
                $target_file = $targetDirectory . '/' . $fileName;
                if ($fileSize > $maxFileSize) {
                    header('HTTP/1.1 400 Bad Request');
                    echo json_encode(["Message" => "File '$fileName' exceeds the maximum allowed size of {$file_size_mb}MB."]);
                    exit;
                }
                if (file_exists($target_file)) {
                    if (!unlink($target_file)) {
                        $allSuccessful = false;
                        break;
                    }
                }
                if (!move_uploaded_file($tmpFile, $target_file)) {
                    $allSuccessful = false;
                    break;
                }
            }
            if ($allSuccessful) {
                header('HTTP/1.1 200 OK');
                echo json_encode(["Message" => "All files have been uploaded successfully."]);
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(["Message" => "Sorry, there was an error uploading one or more files."]);
            }
            }


        } else {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(["Message" => "No files selected for upload."]);
        }

    } else {
        header('HTTP/1.1 401 Unauthorized'); 
        echo json_encode(["Message" => "Unauthorized access. Invalid or missing token."]);
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}