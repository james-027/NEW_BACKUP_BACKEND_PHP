<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {
        try {
            $token = json_decode(getBearerToken(), true);
            $database = $token['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();
            $conn = $processDb->getConnection();
            $searchTerm = strtolower(trim($input['search'] ?? ''));
            $sql = "
                SELECT 
                    p.id,
                    p.date_created,
                    p.date_planned,
                    p.date_actual,
                    p.remark,
                    p.itinerary_id,
                    p.remove,
                    p.created_by,
                    u.first_name,
                    u.last_name,
                    s.outlet_name
                FROM preemptive p
                LEFT JOIN main_db.user u ON u.id = p.user_id
                LEFT JOIN main_db.store s ON s.id = p.store_id
                WHERE
                    LOWER(CONCAT(u.first_name, ' ', u.last_name)) LIKE :search
                    OR LOWER(s.outlet_name) LIKE :search
                    OR LOWER(p.remark) LIKE :search
                    OR DATE_FORMAT(p.date_created, '%M %e %Y') LIKE :search
                    OR DATE_FORMAT(p.date_planned, '%M %e %Y') LIKE :search
                    OR DATE_FORMAT(p.date_actual, '%M %e %Y') LIKE :search
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('search', '%' . $searchTerm . '%');
            $results = $stmt->executeQuery()->fetchAllAssociative();
            $preemptive_list = [];
            foreach ($results as $row) {
                $status_color = null;
                if($row['itinerary_id']){
                    $status_color = "yellow";
                    $itinerary = $processDb->find(configuration_process\itinerary::class, $row['itinerary_id']);
                    if($itinerary->getCheckin()){
                          $status_color = "green";
                    }else{
                         $status_color = "red";
                    }
                }else{
                    $status_color = 'blue';
                }
                if($row['remove'] == false || $row['remove'] == null){
                $preemptive_list[] = [
                    'id' => $row['id'],
                    'user' => $row['first_name'] . " " . $row['last_name'],
                    'store' => $row['outlet_name'],
                    'itinerary'=> $row['itinerary_id'],
                    'date_created' => (new DateTime($row['date_created']))->format('Y-m-d'),
                    'date_planned' => (new DateTime($row['date_planned']))->format('Y-m-d'),
                    'date_actual' => (new DateTime($row['date_actual']))->format('Y-m-d'),
                    'remark' => $row['remark'],
                    'created_by' => $row['created_by'],
                    'status_color' =>$status_color

                ];
                }
            }

            header('HTTP/1.1 200 OK');
            echo json_encode($preemptive_list);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
