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
        if(getBearerToken()){ 
            $token = json_decode(getBearerToken(),true);
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();   
            $identifier = $input['identifier'];
            $user = null;
            if($identifier){
                $user = $input['user_id'];
            }else{
             $user = $token['user_id'];
            }

            $schedules = $processDb->getRepository(process\schedule::class)->findBy(['user_id' => $user]);

            $schedule_list = [];
            foreach($schedules as $schedule){
                  $assign_list = [];

                foreach($schedule->getScheduleuserassign() as $user_assign){

                    $user = $entityManager->find(configuration\user::class,$user_assign->getUser());

                    array_push($assign_list,[ 'value' => $user->getId(),
                    'label' => $user->getStore() ? $user->getStore()->getOutletname() : ($user->getFirstname() ?: '')]);
                }

                array_push($schedule_list,[
                    "id"=>$schedule->getId(),
                    "date_effective"=>$schedule->getDateeffective()->format('Y-m-d H:i:s'),
                    "children"=>$assign_list

                ]);
            }

            
		   function sortById($a, $b) {
                        return $b['id'] - $a['id'];
                    }
                    usort($schedule_list, 'sortById');

        http_response_code(200);
        echo json_encode($schedule_list);


        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }