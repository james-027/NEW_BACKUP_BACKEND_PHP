<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$input = (array)json_decode(file_get_contents('php://input'), true);

$databaseName = 'main_db';
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {
          if($input['database'] === 'process'){
            $databaseName = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($databaseName);
            $entityManager = $dbConnection->getEntityManager();

        }

   $decision = "";
     $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
             if($field){
                    $selectanswer = json_decode($field->getFormula(), true);
                    if($selectanswer[0]['choices'][0]['value']!=""){
                    $result = 0;
                    $operator = "";
                    foreach ($selectanswer as $index => $value) {
                        $count = count($value['choices']);
                        for ($i = 0; $i < $count; $i++) {
                           if (isset($value['choices'][$i]['value'])) {
                            preg_match('/([\d.]+)\s*([<>])\s*([\d.]+)/', $value['choices'][$i]['value'], $matches);
                             if (count($matches) != 4) {
                                throw new Exception("Invalid comparison expression: $expression");
                             }
                           }
                            $field = $entityManager->find(configuration_process\field::class,$matches[1]);
                            $selectanswer = json_decode($field->getFormula(), true);
                            $value1 = (float)   $selectanswer['answer'];
                            $operator = $matches[2];
                            $value2 = (float) $matches[3];

try {
    if ($value1<$value2){
        $decision  = ['value'=>$value1,'color'=>$value['decision'][$i]['value']];
    }
} catch (Exception $e) {}

                         }
                      }
                    }
                 }
                    echo header('HTTP/1.1 200 OK');
                    echo json_encode($decision);

    }
    
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}