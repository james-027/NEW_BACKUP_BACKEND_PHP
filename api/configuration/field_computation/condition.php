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

    $decision = false;
       $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
                if($field){
                    if($field->getFormula()!=""){
                     $selectanswer = json_decode($field->getFormula(), true);
                        list($variable, $valueStr) = explode('=', $selectanswer[0]['formula']);
                        $variableName = trim($variable);
                        $value = trim($valueStr);
                        $field = $entityManager->find(configuration_process\field::class,$valueStr);
                        $selectanswer = json_decode($field->getFormula(), true);
                        if($selectanswer[0]['answer']===""){
                            $decision="true";
                            $field= $entityManager->find(configuration_process\field::class,$input['field_id']);
                            $selectanswer = json_decode($field->getFormula(), true);
                            $selectanswer[0]['check']="true";
                            $field->setAnswer(json_encode($selectanswer));
                            $entityManager->flush();
                        }else{
                             if($variableName===$selectanswer[0]['answer']){
                                $decision="false";
                               $field= $entityManager->find(configuration_process\field::class,$input['field_id']);
                            $selectanswer = json_decode($field->getFormula(), true);
                                $selectanswer[0]['check']="false";
                                $field->setAnswer(json_encode($selectanswer));
                                $entityManager->flush();
                            }else{
                                $decision="true";
                              $field= $entityManager->find(configuration_process\field::class,$input['field_id']);
                                $selectanswer = json_decode($field->getFormula(), true);
                                $selectanswer[0]['check']="true";
                                $field->setAnswer(json_encode($selectanswer));
                                $entityManager->flush();
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