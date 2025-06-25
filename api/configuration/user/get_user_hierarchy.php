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
        $user = $entityManager->find(configuration\user::class, $input['user_id']);

        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            exit;
        }

        $visited = [];
        $identifier = $input['identifier'];
       function buildUserTree($user, &$visited, $identifier) {
    $userId = $user->getId();

    if (in_array($userId, $visited)) {
        return null;
    }

    $visited[] = $userId;
    $children = [];

    foreach ($user->getUserlink() as $child) {
        $userTypeId = $child->getUsertype()->getId();
         if ($identifier && $userTypeId === 2) {
            continue;
        }


        $childData = buildUserTree($child, $visited, $identifier);
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
            $tree = buildUserTree($linkedUser, $visited,$identifier);
            if ($tree !== null) {
                $finalOutput[] = $tree;
            }
        }

        http_response_code(200);
        echo json_encode($finalOutput);

    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
