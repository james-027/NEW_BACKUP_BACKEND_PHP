<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../database.php';

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$parent_directory = dirname(__DIR__);
$icon_directory = dirname($parent_directory);

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $file = basename($_GET['file']);
    $path = $_GET['path'];
    $targetDirectory = $icon_directory . "/../digital_workspace_file/icon/" . $path . '/' . $file;

    if (file_exists($targetDirectory)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $targetDirectory);
        finfo_close($finfo);

        ob_clean();
        flush();

        header('HTTP/1.1 200 OK');
        header("Content-Type: $mimeType");

        readfile($targetDirectory);
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(["Message" => "File not found"]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
