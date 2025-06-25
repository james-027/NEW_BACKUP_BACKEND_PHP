<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';  
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager(); 

$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (getBearerToken()) { 
        try {
            $token = json_decode(getBearerToken(), true);
            $database = $token['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();   

            $schedules = $processDb->getRepository(process\schedule::class)->findBy([
                'user_id' => $token['user_id']
            ]);

            $timezone = new DateTimeZone('Asia/Manila');
            $now = new DateTime('now', $timezone);
            $today = $now->format('Y-m-d');
            $nowTime = $now->format('H:i:s');

            $todaySchedules = [];

            foreach ($schedules as $schedule) {
                if ($schedule->getDateeffective()->format('Y-m-d') === $today) {
                    $todaySchedules[] = $schedule;
                }
            }

            if (empty($todaySchedules)) {
                http_response_code(200);
                echo json_encode([]);
                exit;
            }

            usort($todaySchedules, function ($a, $b) {
                return strcmp($a->getDateeffective()->format('H:i:s'), $b->getDateeffective()->format('H:i:s'));
            });


            $selectedSchedule = null;
            foreach ($todaySchedules as $schedule) {
                if ($nowTime <= $schedule->getDateeffective()->format('H:i:s')) {
                    $selectedSchedule = $schedule;
                    break;
                }
            }

            if (!$selectedSchedule) {
                $selectedSchedule = end($todaySchedules);
            }

            $assign_list = [];
            foreach ($selectedSchedule->getScheduleuserassign() as $user_assign) {
                $user = $entityManager->find(configuration\user::class, $user_assign->getUser());
                if (!$user) continue;

                $assign_list[] = [
                    'value' => $user->getId(),
                    'label' => $user->getStore()
                        ? $user->getStore()->getOutletname()
                        : ($user->getFirstname() ?: '')
                ];
            }


            http_response_code(200);
            echo json_encode($assign_list);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["Message" => $e->getMessage()]);
        }

    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Unauthorized"]);
    }

} else { 
    http_response_code(405);
    echo json_encode(["Message" => "Method Not Allowed"]);
}
