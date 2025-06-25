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
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) { 
        $field = $entityManager->find(configuration_process\field::class, $input['field_id']);
        $field_list = [];
        function getLinkedFields($linkedField, $entityManager) {
            $fieldDetails = [];
            $type = $entityManager->find(configuration_process\field_type::class, $linkedField->getFieldtype());
            $fieldDetails[] = [
                'id' => $linkedField->getId(),
                'series' => $linkedField->getSeries(),
                'answer' => $linkedField->getAnswer(),
                'formula' => $linkedField->getFormula(),
                'type' => $type->getDescription(), 
            ];
            if ($linkedField->getFieldlink()) {
                foreach ($linkedField->getFieldlink() as $subLinkedField) {
                    $fieldDetails = array_merge($fieldDetails, getLinkedFields($subLinkedField, $entityManager));
                }
            }
            return $fieldDetails;
            }
            foreach ($field->getFieldlink() as $linkedField) {
                $field_list = array_merge($field_list, getLinkedFields($linkedField, $entityManager));
            }
            function sortById($a, $b) {
                return $a['series'] - $b['series'];
            }
            usort($field_list, 'sortById'); 
            header("HTTP/1.1 200 OK");
            echo json_encode($field_list);
    }
} else { 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}