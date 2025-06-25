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
        if (empty($searchTerm)) {
            header('HTTP/1.1 200 OK');
            echo json_encode([]);
            exit;
        }
        
        try {
            $sql = "
   SELECT 
    f.id AS id,  -- Always return the main form's ID
    f.title AS title,
    f.date_created AS date_created,
    f.priority AS priority,
    f.version AS parent_version,
    related_form.version AS publish_version,
    CONCAT(ft.description) AS form_type,
    CONCAT(related_form.date_created) AS publish_date,
    CONCAT(u.first_name, ' ', u.last_name) AS created_by
    FROM table_form t
    LEFT JOIN form f ON t.form_id = f.id  -- Join main form
    LEFT JOIN user u ON f.created_by = u.id  -- Join user who created the form
    LEFT JOIN form_type ft ON f.type_id = ft.id  -- Join form type
    LEFT JOIN form_form ff ON t.form_id = ff.form_id  -- Link to form_form table
    LEFT JOIN form related_form ON ff.form_related_id = related_form.id  -- Join related form
    WHERE 
    DATE_FORMAT(f.date_created, '%M %d %Y') LIKE CONCAT('%', :search, '%') OR
        f.title LIKE CONCAT('%', :search, '%') OR
        f.priority LIKE CONCAT('%', :search, '%') OR
        f.version LIKE CONCAT('%', :search, '%') OR
        ft.description LIKE CONCAT('%', :search, '%') OR
        CONCAT(u.first_name, ' ', u.last_name) LIKE CONCAT('%', :search, '%') OR
        f.id LIKE CONCAT('%', :search, '%') OR 
        related_form.version LIKE CONCAT('%', :search, '%') OR
        DATE_FORMAT(related_form.date_created, '%M %d %Y') LIKE CONCAT('%', :search, '%')

            ";
            
            $query = $entityManager->getConnection()->prepare($sql);
            $query->bindValue(':search', '%' . strtolower($searchTerm) . '%'); 

            $forms = $query->executeQuery()->fetchAllAssociative();
            $form_list = [];
            foreach ($forms as $form) {
                $form_list[] = [
                    'id' => $form['id'],
                    'title' => $form['title'],
                    'date_created' => $form['date_created'],
                    'created_by' => $form['created_by'],
                    'parent_version'=>$form['parent_version'],
                    'publish_version'=>$form['publish_version'],
                    'form_type'=>$form['form_type'],  
                    'priority'=>$form['priority'],  
                    'publish_date'=>$form['publish_date'],  
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
?>
