<?php
// Enable error reporting for debugging
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
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){  
            $input_field = $entityManager->find(configuration_process\field::class,$input['field_id']);
            $formula = $input_field->getFormula();
            $compare_formula = json_decode($formula, true);
            try{
                if($compare_formula['type'] == "radio"){
                    $fields = $entityManager->getRepository(configuration_process\field::class)->findAll();
                    foreach ($input_field->getTaskfield() as $task_field) {
                        foreach ($task_field->getTaskfield() as $field) {
                            if ($field->getRadio() == $input_field->getRadio()) {
                                $get_formula = $field->getFormula();
                                $new_formula = json_decode($get_formula, true);
                                if ($field->getId() == $input_field->getId()) {
                                    $new_formula['checked'] = true;
                                    $new_formula['answer'] = true;
                                    $field->setAnswer("true");
                                } else {
                                    $new_formula['checked'] = false;
                                    $new_formula['answer'] = false;
                                    $field->setAnswer("false");
                                }
                                $field->setFormula(json_encode($new_formula));
                            }
                        }
                    }
                    
                    $entityManager->flush();
                    
                }else{
                    $select_formula = json_decode($input['formula'], true);
                    $input_field->setFormula($input['formula']);
                    $input_field->setAnswer($select_formula['answer']);
                    $entityManager->flush();
                }

            }catch(Exception $e){
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            }
                echo header("HTTP/1.1 200 OK");
                echo json_encode(['Message' => "Changed Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    