<?php
ini_set('display_Messages', 1);
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
        $token = json_decode(getBearerToken(), true);
        $authUser = $entityManager->find(configuration\user::class, $token['user_id']);

        if (!$authUser) {
            http_response_code(404);
            echo json_encode(["Message" => "User not found"]);
            exit;
        }

        $searchTerm = isset($input['search']) ? trim(strtolower($input['search'])) : '';

        $visited = [];
        $flatUsers = [];
        function collectLinkedUsers($user, &$visited, &$flatUsers) {
            $userId = $user->getId();
            if (in_array($userId, $visited)) {
                return;
            }
            $visited[] = $userId;

            foreach ($user->getUserlink() as $linked) {

                if($linked->getUsertype()->getId() !== 2){
                     $linkedId = $linked->getId();
                if (!in_array($linkedId, $visited)) {
                    $flatUsers[] = $linked;
                    collectLinkedUsers($linked, $visited, $flatUsers);
                }
                }

               
            }
        }

        collectLinkedUsers($authUser, $visited, $flatUsers);

        $filtered = array_filter($flatUsers, function($user) use ($searchTerm) {
            $firstName = strtolower($user->getFirstname() ?: '');
            $lastName = strtolower($user->getLastname() ?: '');
            $username = strtolower($user->getUsername() ?: '');
            $userType = strtolower($user->getUsertype()?->getDescription() ?: '');
            $storeName = strtolower($user->getStore()?->getOutletname() ?: '');

            return $searchTerm === '' || (
                strpos($firstName, $searchTerm) !== false ||
                strpos($lastName, $searchTerm) !== false ||
                strpos($username, $searchTerm) !== false ||
                strpos($userType, $searchTerm) !== false ||
                strpos($storeName, $searchTerm) !== false
            );
        });

        $userList = array_map(function($user) {
            return [
                'id' => $user->getId(),
                'first_name' => $user->getStore() 
                    ? $user->getStore()->getOutletname() 
                    : ($user->getFirstname() ?: ''), 
                'last_name' => $user->getLastname(),
                'user_type' => $user->getUsertype()?->getDescription()
            ];
        }, $filtered);

        http_response_code(200);
        echo json_encode(array_values($userList));
    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["message" => "Method Not Allowed"]);
}
