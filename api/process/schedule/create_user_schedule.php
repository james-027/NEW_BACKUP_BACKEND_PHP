<?php
// Enable error reporting for debugging
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
        $user = $entityManager->find(configuration\user::class, $token['user_id']);

        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            exit;
        }
        $visited = [];
        function buildUserTree($user, &$visited) {
            $userId = $user->getId();
            if (in_array($userId, $visited)) {
                return null;
            }
            $visited[] = $userId;

            $children = [];
            foreach ($user->getUserassign() as $child) {
                

                $childData = buildUserTree($child, $visited);
                if ($childData !== null) {
                    $children[] = $childData;
                }
            }

            return [
                'id' => $user->getId(),
                'first_name' => $user->getStore() 
                    ? $user->getStore()->getOutletname() 
                    : ($user->getFirstname() ?: ''), 
                'last_name' => $user->getLastname(),
                'user_type' => $user->getUsertype() 
                    ? $user->getUsertype()->getDescription() 
                    : null,
                'children' => $children
            ];
        }

        $finalOutput = [];
        foreach ($user->getUserlink() as $linkedUser) {
            $tree = buildUserTree($linkedUser, $visited);
            if ($tree !== null) {
                $finalOutput[] = $tree;
            }
        }

        http_response_code(200);
        echo json_encode($finalOutput);


        // $user = $entityManager->find(configuration\user::class, $user_id);
        $new_schedule = new process\schedule;
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime($input['schedule'], $timezone);
        $new_schedule->setDateeffective($date);
        $new_schedule->setUser($user->getId());
        $processDb->persist($new_schedule);
        $user_assigns = $child;
        foreach($user_assigns as $user_assign){
            $new_user_assign = new process\user_assign;
            foreach([$user_assign] as $new){
                $new_user_assign->setUser($new->getId());
                $processDb->persist($new_user_assign);
            }
        }
        $processDb->flush();
        $schedule_user_assign = new process\schedule_user_assign;
        $schedule_user_assign->setUserassign($new_user_assign->getId());
        $schedule_user_assign->setSchedule($new_schedule->getId());
        $processDb->persist($schedule_user_assign);
        $processDb->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message"=>"Schedule generated!"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }