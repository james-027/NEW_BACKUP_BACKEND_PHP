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

        $token = json_decode(getBearerToken(), true);
        $identifier = $input['identifier'];
           $user_id = null;
            if($identifier){
                $user_id = $input['user_id'];
            }else{
             $user_id = $token['user_id'];
            }
            
        $user = $entityManager->find(configuration\user::class, $user_id); 


        if (!$user) {
            http_response_code(404);
            echo json_encode(["Message" => "User not found"]);
            exit;
        }

        $visited = [];
        $storeUsers = [];

       function collectStoreUsers($user, &$visited, &$storeUsers) {
    $userId = $user->getId();

    if (isset($visited[$userId])) {
        return;
    }

    $visited[$userId] = true;

    $storeChildren = [];

    foreach ($user->getUserlink() as $child) {
        $childUsertype = $child->getUsertype(); 
        $isStore = $childUsertype && $childUsertype->getDescription() === "STORE";

        if ($isStore) {
            $storeChildren[] = [
                'value' => $child->getId(),
                'label' => $child->getStore() ? $child->getStore()->getOutletname() : ($child->getFirstname() ?: ''),
            ];
        }
    }
    if (count($storeChildren) > 0) {
        foreach ($storeChildren as $storeUser) {
            $storeUsers[] = $storeUser;
        }
        return;
    }

    foreach ($user->getUserlink() as $child) {
        collectStoreUsers($child, $visited, $storeUsers);
    }

    foreach ($user->getBidirectional() as $child) {
        collectStoreUsers($child, $visited, $storeUsers);
    }
}


        collectStoreUsers($user, $visited, $storeUsers);

        http_response_code(200);
        echo json_encode($storeUsers);

    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["Message" => "Method Not Allowed"]);
}
