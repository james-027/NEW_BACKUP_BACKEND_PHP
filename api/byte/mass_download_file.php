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
$path = $input['path'] ?? '';
$filenames = $input['filename'] ?? '';

$targetDirectory = realpath($icon_directory . "/../digital_workspace_file/icon/" . $path);
if (!$targetDirectory || !is_dir($targetDirectory)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(["Message" => "Invalid path."]);
    exit;
}

$files = explode('`', $filenames);
date_default_timezone_set('Asia/Manila');
$micro = microtime(true);
$milliseconds = sprintf("%03d", ($micro - floor($micro)) * 1000);
$uniqueId = uniqid();
$zipFilename = $input['zip_name'] ."_{$uniqueId}_"   . date('Y_m_d_H_i_s_') .$milliseconds. '.zip';
$tmpZipPath = sys_get_temp_dir() . '/' . $zipFilename; 

$zip = new ZipArchive();
if ($zip->open($tmpZipPath, ZipArchive::CREATE) !== true) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(["Message" => "Could not create zip file."]);
    exit;
}

foreach ($files as $file) {
    $filePath = realpath($targetDirectory . '/' . basename($file));
    if ($filePath && strpos($filePath, $targetDirectory) === 0 && file_exists($filePath)) {
        $zip->addFile($filePath, basename($filePath));
    }
}
$zip->close();

if (file_exists($tmpZipPath)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
    header('Content-Length: ' . filesize($tmpZipPath));
    readfile($tmpZipPath);
    unlink($tmpZipPath); // Clean up the temporary zip file
    exit;
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(["Message" => "Failed to create ZIP archive."]);
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