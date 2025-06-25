<?php
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

$input = (array)json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) { 
try {
    $field = $entityManager->find(configuration_process\field::class, $input['field_id']);
    $origin = new configuration\origin;
    $type = $entityManager->find(configuration_process\field_type::class, $field->getFieldtype());
    $path = $entityManager->find(configuration\path::class, $type->getPath());
    
    header('HTTP/1.1 200 OK');
    echo json_encode(
    [
        'id'=>$field->getId(),
        'style'=>$field->getStyle(),
        'type_id'=>$field->getFieldtype(),
        'row_occupied'=>$field->getRowoccupied(),
        'col_occupied'=>$field->getColoccupied(),
        "activate_style"=>$field->getActivatestyle(),
        "row_no"=>$field->getRowno(),
        "col_no"=>$field->getColno(),
        "formula"=>$field->getFormula(),
        "question"=>$field->getQuestion(),
        "radio"=>$field->getRadio(),
        "picture"=>$origin->getOrigin($path->getDescription(),$type->getIcon()),
        "label"=>$type->getLabel(),
        "field_type"=>$type->getDescription(),
    ]
    );
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
?>

