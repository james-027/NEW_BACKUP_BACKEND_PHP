<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
$check = false;
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){
            $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
            $task->setSeries($input['series']-1);
            $entityManager->flush();
            $task_list = [];
            $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
                        foreach ($form->getFormtask() as $task) {
                            array_push($task_list,['series'=>$task->getSeries(),'id'=>$task->getId()]);
                          }
                    function sortById($a, $b) {
                      return  $a['series'] - $b['series'];
                    }
                  usort($task_list, 'sortById');
                      foreach ($task_list  as $index => $series) {
                        $change_series = $entityManager->find(configuration_process\task::class,$series['id']);
                        $change_series->setSeries($index);
                        $entityManager->flush();
                      }
                  echo header("HTTP/1.1 200 OK");
                  echo json_encode(['Message' => "Task Moved Successfully"]);
        }
    }
  else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
  }