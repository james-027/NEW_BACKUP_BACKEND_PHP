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
    $task_list= [];
    $task = $entityManager->find(configuration_process\task::class, $input['task_id']);
        $validation_list = [];
        $assign_list = [];
        foreach($task->getTaskvalidation() as $validation){
            $user_profile = $entityManager->find(configuration_process\user_type::class, $validation->getUsertype());
            array_push($validation_list,['id'=>$validation->getId(),"validator"=>$user_profile->getDescription() ? $user_profile->getDescription(): "","valid"=>$validation->getValid()]);
        }
        foreach($task->getTaskassign() as $assign){
            $user_profile = $entityManager->find(configuration_process\user_type::class, $assign->getUsertype());
            array_push($assign_list,['id'=>$assign->getId(),"assignee"=>$user_profile->getDescription() ? $user_profile->getDescription(): "","valid"=>$assign->getValid()]);
        }
        $status = $entityManager->find(configuration_process\status::class,$task->getStatus());
    header('HTTP/1.1 200 OK');
    echo json_encode(
    ['id' => $task->getId(),
    'title' => $task->getTitle(),
    'description' => $task->getDescription(),
    'series' => $task->getSeries(),
    "field"=>count($task->getTaskfield()),
    'validation' => $validation_list,
    'assigned' => $assign_list,
    'style'=>$task->getStyle(),
    'row_set'=>$task->getRowset(),
    'col_set'=>$task->getColset(),]
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

