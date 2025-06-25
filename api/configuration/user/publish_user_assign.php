<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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

        $token = json_decode(getBearerToken(),true);
        $user = $entityManager->find(configuration\user::class, $token['user_id']);

        $databaseName = $token['database'];

        $dbConnection = new DatabaseConnection($databaseName);
        $process_db = $dbConnection->getEntityManager();

        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            exit;
        }

        $visited = [];
        function create_user_schedule($user,$process_db){
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
           $new_schedule = new process\schedule;
           $timezone = new DateTimeZone('Asia/Manila');
         $date = new DateTime($input['date_effective'], $timezone);
        $new_schedule->setDateeffective($date);
           $new_schedule->setUser($user->getId());
           foreach ($user->getUserassign() as $assign) {
              $new_user_assign = new process\user_assign;
              $new_user_assign->setUser($assign->getId());
              $process_db->persist($new_user_assign);
              $process_db->flush();
              $new_schedule->setScheduleuserassign($new_user_assign);
           }
           $process_db->persist($new_schedule);
           $process_db->flush();
        }

        function buildUserTree($user, &$visited,$process_db) {
            $userId = $user->getId();
            if (in_array($userId, $visited)) {
                return null;
            }
            $visited[] = $userId;

            $children = [];

            if(count($user->getUserassign())){
                create_user_schedule($user,$process_db);
            }
               foreach ($user->getUserlink() as $child) {

                $childData = buildUserTree($child, $visited,$process_db);
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
            $tree = buildUserTree($linkedUser, $visited,$process_db);
            if ($tree !== null) {
                $finalOutput[] = $tree;
            }
        }

        http_response_code(200);
        echo json_encode(['Message'=>"Schedule Publish Generated!"]);

    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}