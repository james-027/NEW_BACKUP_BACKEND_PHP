<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 

$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){  
            $database = json_decode(getBearerToken(),true)['database'];
            $dbConnection = new DatabaseConnection($database);
            $proccessDb = $dbConnection->getEntityManager();
            $itinerary = $proccessDb->find(configuration_process\itinerary::class , $input['itinerary_id']);
            $store = $entityManager->find(configuration\store::class , $itinerary->getStore());
            $user_coordinates = parseCoordinates( $input['coordinates']);
            $user_latitude = $user_coordinates[0];            
            $user_longitude = $user_coordinates[1];            
            $distance = DistanceCalculator::calculateDistance($store->getLatitude(),$store->getLongitude(),$user_latitude,$user_longitude);
            $status = ($distance <= $store->getDistance()) ? "INSIDE" : "OUTSIDE";
            if($input['identifier'] == "checkin"){
            if($itinerary->getCheckin() === null){
                if($itinerary->getCheckintime() === null){
                    if($status === "INSIDE"){
                        $itinerary->setCheckin(true);
                    }else if ($status ==="OUTSIDE"){
                        $itinerary->setCheckin(false);
                    }
                    $itinerary->setCheckinlatitude($user_latitude);
                    $itinerary->setCheckinlongitude($user_longitude);
                    $itinerary->setCheckinremark($input['remark']);
                    $timezone = new DateTimeZone('Asia/Manila');
                    $date = new DateTime('now', $timezone);
                    $itinerary->setCheckintime($date);
                    $itinerary->setCheckinimage($input['image']);
                    $proccessDb->flush();
                    echo header("HTTP/1.1 200 OK");
                    echo json_encode(['Message' => "{$status}"]);
                }
            }else if($itinerary->getCheckin()===false){
                    if($status == "INSIDE"){
                        $itinerary->setCheckin(true);
                    }
                    $itinerary->setCheckinlatitude($user_latitude);
                    $itinerary->setCheckinlongitude($user_longitude); 
                    $proccessDb->flush();
                    echo header("HTTP/1.1 200 OK");
                    echo json_encode(['Message' => "{$status}"]);
                }else if($itinerary->getCheckin() === true){
                        echo header("HTTP/1.1 200 OK");
                        echo json_encode(['Message' => "Completed"]);
                }
            }else if ($input['identifier'] == "checkout"){
                if($itinerary->getCheckin()!== null){
                    if($itinerary->getCheckout()===null){
                        if($status === "INSIDE"){
                        $itinerary->setCheckout(true);
                    }else if ($status ==="OUTSIDE"){
                        $itinerary->setCheckout(false);
                    }
                    $validation = null;
                    if($itinerary->getCheckout() === false){
                        $validation = false;
                    }else if ($itinerary->getCheckout()===true && $itinerary->getCheckin()===false){
                        $validation = false;
                    }
                    else if ($itinerary->getCheckout()===false && $itinerary->getCheckin()===true){
                        $validation = false;
                    }
                    else if ($itinerary->getCheckout()===true && $itinerary->getCheckin()===true){
                        $validation = true;
                    }
                    $itinerary->setCheckoutlatitude($user_latitude);
                    $itinerary->setCheckoutlongitude($user_longitude);
                    $itinerary->setCheckoutremark($input['remark']);
                    $itinerary->setValidation($validation);
                    $timezone = new DateTimeZone('Asia/Manila');
                    $date = new DateTime('now', $timezone);
                    $itinerary->setCheckouttime($date);
                    $itinerary->setCheckoutimage($input['image']);
                    $proccessDb->flush();
                    echo header("HTTP/1.1 200 OK");
                    echo json_encode(['Message' => "{$status}"]);
                    }else if($itinerary->getCheckout()===false){
                        if($status == "INSIDE"){
                        $itinerary->setCheckout(true);
                    }
                        $validation = null;
                    if($itinerary->getCheckout() === false){
                        $validation = false;
                    }else if ($itinerary->getCheckout()===true && $itinerary->getCheckin()===false){
                        $validation = false;
                    }
                    else if ($itinerary->getCheckout()===false && $itinerary->getCheckin()===true){
                        $validation = false;
                    }
                    else if ($itinerary->getCheckout()===true && $itinerary->getCheckin()===true){
                        $validation = true;
                    }
                        $itinerary->setValidation($validation);
                        $itinerary->setCheckoutlatitude($user_latitude);
                        $itinerary->setCheckoutlongitude($user_longitude); 
                        $proccessDb->flush();
                    echo header("HTTP/1.1 200 OK");
                    echo json_encode(['Message' => "{$status}"]);
                        
                    }else if($itinerary->getCheckout() === true){
                        echo header("HTTP/1.1 200 OK");
                        echo json_encode(['Message' => "Completed"]);
                }
                }else{
                        echo header("HTTP/1.1 200 OK");
                        echo json_encode(['Message' => "Check In First"]);
                }
            }
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    function parseCoordinates(string $coordinates): ?array {
    $parts = explode(',', $coordinates);
    if (count($parts) === 2) {
        $lat = floatval(trim($parts[0]));
        $lon = floatval(trim($parts[1]));
        if (is_numeric($lat) && is_numeric($lon)) {
            return [$lat, $lon];
        }
    }
    return null;
}

class DistanceCalculator {
    private const RADIUS_OF_EARTH_KM = 6371; 
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float {
        $latDistance = deg2rad($lat2 - $lat1);
        $lonDistance = deg2rad($lon2 - $lon1);
        $a = sin($latDistance / 2) * sin($latDistance / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDistance / 2) * sin($lonDistance / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = self::RADIUS_OF_EARTH_KM * $c * 1000; 
        return $distance;
    }
}