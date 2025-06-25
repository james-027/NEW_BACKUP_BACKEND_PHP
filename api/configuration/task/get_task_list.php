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
$form = $entityManager->find(configuration_process\form::class, $input['form_id']);
$formTaskIds = [];

foreach ($form->getFormtask() as $task) {
    $formTaskIds[] = $task->getId(); 
}

$searchTerm = isset($input['search']) ? trim($input['search']) : '';

try {
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder->select('t')
        ->from(configuration_process\task::class, 't')
        ->join(configuration_process\status::class, 's', 'WITH', 't.status_id = s.id') 
        ->where($queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('LOWER(t.title)', ':search'),
            $queryBuilder->expr()->like('LOWER(t.description)', ':search'),
            $queryBuilder->expr()->like('LOWER(s.description)', ':search') 
        ))
        ->andWhere($queryBuilder->expr()->in('t.id', ':formTaskIds'))
        ->setParameter('formTaskIds', $formTaskIds)
        ->setParameter('search', '%' . strtolower($searchTerm) . '%');
    $tasks = $queryBuilder->getQuery()->getResult();
    $task_list = [];
    foreach ($tasks as $task) {
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
        array_push($task_list, [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'series' => $task->getSeries(),
             'assigned' => $assign_list,
            'validation' => $validation_list,
        ]);
    }
    function sortById($a, $b) {
        return  $a['series'] - $b['series'];
    }
    
    usort($task_list, 'sortById'); 
    header('HTTP/1.1 200 OK');
    echo json_encode($task_list);
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

