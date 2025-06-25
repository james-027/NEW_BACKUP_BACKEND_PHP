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
        $searchTerm = isset($input['search']) ? trim($input['search']) : '';

        try {
            $sql = "
                SELECT DISTINCT 
                    f.id AS id,
                    f.title AS title,
                    f.date_created AS date_created,
                    f.version AS version,
                    ft.id AS form_type_id,
                    ft.description AS form_type,
                    CONCAT(u.first_name, ' ', u.last_name) AS created_by
                FROM {$databaseName}.form_form ff
                INNER JOIN {$databaseName}.form f ON f.id = ff.form_id
                LEFT JOIN {$databaseName}.form_type ft ON f.type_id = ft.id
                LEFT JOIN {$databaseName}.user u ON f.created_by = u.id
                WHERE 
                    (:search IS NULL OR :search = '' OR (
                        f.title LIKE :searchLike OR
                        f.version LIKE :searchLike OR
                        DATE_FORMAT(f.date_created, '%M %d %Y') LIKE :searchLike OR
                        ft.description LIKE :searchLike OR
                        CONCAT(u.first_name, ' ', u.last_name) LIKE :searchLike
                    ))
            ";

            $conn = $entityManager->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':search', $searchTerm);
            $stmt->bindValue(':searchLike', '%' . strtolower($searchTerm) . '%');
            $results = $stmt->executeQuery()->fetchAllAssociative();

            $form_list = [];

            foreach ($results as $form) {
                $publish_form = $entityManager->find(configuration_process\form::class, $form['id']);
                $formlinks = $publish_form->getFormlink();
                $version_count = count($formlinks);
                $last_version = null;

                for ($i = $version_count - 1; $i >= 0; $i--) {
                    $dateStr = $formlinks[$i]->getDatecreated()->format('d-m-Y g:i:s A');
                    if (check_lapse_date($dateStr)) {
                        $last_version = $formlinks[$i];
                        break;
                    }
                }

                $form_list[] = [
                    'id' => $form['id'],
                    'title' => $form['title'],
                    'date_created' => $form['date_created'],
                    'created_by' => $form['created_by'],
                    'version' => $form['version'],
                    'form_type' => $form['form_type'],
                    'form_type_id' => $form['form_type_id'],
                    'publish_version' => $last_version ? $last_version->getVersion() : null,
                    'publish_date' => $last_version ? $last_version->getDatecreated()->format('Y-m-d H:i:s') : null,
                ];
            }

            http_response_code(200);
            echo json_encode($form_list);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["Message" => "Method Not Allowed"]);
}


function check_lapse_date($dateTimeString) {
    $timezone = new DateTimeZone('Asia/Manila');
    $providedDateTime = new DateTime($dateTimeString, $timezone);
    $currentDateTime = new DateTime('now', $timezone);
    return $providedDateTime < $currentDateTime;
}
