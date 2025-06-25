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
        // if (empty($searchTerm)) {
        //     header('HTTP/1.1 200 OK');
        //     echo json_encode([]);
        //     exit;
        // }
        try {
        $sql = "
            SELECT 
            f.id AS id, 
            t.id AS table_form_id,
            t.hide AS hide,
            f.title AS title, 
            f.date_created AS date_created, 
            f.version AS version,
            ft.id AS form_type_id,
            CONCAT(ft.description) AS form_type,
            CONCAT(u.first_name, ' ', u.last_name) AS created_by
            FROM {$databaseName}.table_form t
            LEFT JOIN {$databaseName}.form f ON t.form_id = f.id 
            LEFT JOIN main_db.user u ON f.created_by = u.id
            LEFT JOIN main_db.form_type ft ON f.type_id = ft.id
            WHERE 
            (DATE_FORMAT(f.date_created, '%M %e %Y') LIKE CONCAT('%', :search, '%') OR
            f.title LIKE CONCAT('%', :search, '%') OR
            f.version LIKE CONCAT('%', :search, '%') OR
            ft.description LIKE CONCAT('%', :search, '%') OR
            CONCAT(u.first_name, ' ', u.last_name) LIKE CONCAT('%', :search, '%'));
            ";
            $query = $entityManager->getConnection()->prepare($sql);
            $query->bindValue(':search', '%' . strtolower($searchTerm) . '%'); 
            $forms = $query->executeQuery()->fetchAllAssociative();
            $form_list = [];
            foreach ($forms as $form) {

                if (!empty($form['hide'])) {
                    continue;
                }
                $publish_form = $entityManager->find(configuration_process\form::class,$form['id']);
                $version_count = count($publish_form->getFormlink());
                $date_to_check = new \DateTime();
                $last_version = null;
                for ($i = $version_count - 1; $i >= 0; $i--) {
                    if (check_lapse_date($publish_form->getFormlink()[$i]->getDatecreated()->format('d-m-Y g:i:s A'))) {
                        $last_version = $publish_form->getFormlink()[$i];
                    break;
                    }
                }
                
                $form_list[] = [
                    'id' => $form['id'],
                    'table_form_id' => $form['table_form_id'],
                    'title' => $form['title'],
                    'date_created' => $form['date_created'],
                    'created_by' => $form['created_by'],
                    'version'=>$form['version'],
                    'form_type'=>$form['form_type'],  
                    'form_type_id'=>$form['form_type_id'],  
                    'publish_version'=>$last_version?$last_version->getVersion():null,
                    'publish_date'=>$last_version?$last_version->getDatecreated()->format('Y-m-d H:i:s'):null,
                ];
            }
            header('HTTP/1.1 200 OK');
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
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}

function check_lapse_date($dateTimeString) {
    $timezone = new DateTimeZone('Asia/Manila');
    $providedDateTime = new DateTime($dateTimeString, $timezone);
    $currentDateTime = new DateTime('now', $timezone);
    return $providedDateTime < $currentDateTime;
}

?>
