<?php
$current_script = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
if ($current_script !== 'login'&&
    $current_script !== 'create_super_admin'&&
    $current_script !== 'get_icon'&&
    $current_script !== 'get_meta'&&  $current_script !== 'upload_store_details' && $current_script !== 'automation_form'&& $current_script !== 'automation_itinerary'
    
){
function getBearerToken() {
    $bearer_token = '';
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($auth_header, 'Bearer ') === 0) {
            $bearer_token = substr($auth_header, 7);
        }
    }
    if (empty($bearer_token) && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
            if (strpos($auth_header, 'Bearer ') === 0) {
                $bearer_token = substr($auth_header, 7);
            }
        }
    }
    if (empty($bearer_token)) {
        echo json_encode(["Message" => "Unauthorized, Bearer token is missing."]);
        exit;
    }
    if (strlen($bearer_token) < 20) { 
        echo json_encode(["Message" => "Invalid Token Format, Token too short."]);
        exit;
    }
    $tokens = new configuration\tokens;  
    $databaseName = "main_db";
    $dbConnection = new DatabaseConnection($databaseName);
    $entityManager = $dbConnection->getEntityManager();
    $check = $entityManager->find(configuration\user::class,$tokens->decodeToken($bearer_token)['user_id']);
    if(!$check->getActivate()){
        echo json_encode(["Message" => "Unauthorized, Invalid or Expired Token."]);
        exit;
    }
    if($check){
        $timezone = new DateTimeZone('Asia/Manila');
        $currentDateTime = new DateTime('now', $timezone);
    return json_encode([
        "token"=>$bearer_token,
        "user_id"=>$tokens->decodeToken($bearer_token)['user_id'],
        "database"=>$check->getDatabasename() ? "dws_db_" . $check->getDatabasename() : "dws_db_" . $currentDateTime->format('Y'),
        
    ]);
    }else{
        echo json_encode(["Message" => "Unauthorized, Invalid or Expired Token."]);
        exit;
    }
    }
    $tokens = new configuration\tokens;  
    $bearer_token = json_decode(getBearerToken(), true)['token'];  
    try {
        $result = $tokens->getValidation($bearer_token);
    if ($result) {
            return true;
        } else {
            echo json_encode(["Message" => "Unauthorized, Invalid or Expired Token."]);
        }
        exit;
    } catch (Exception $e) { 
        echo json_encode(["Message" => "Error validating token: " . $e->getMessage()]);
        exit;
    }
}
?>
