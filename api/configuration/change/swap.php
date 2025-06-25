<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers:Content-Type, Authorization");

require_once __DIR__ . '/../../../database.php';

$input = (array) json_decode(file_get_contents('php://input'), TRUE);

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$client_repository = $entityManager->getRepository(MainDb\Configuration\client::class);
$existing_domain = $client_repository->findOneBy(['domain' => $_SERVER['HTTP_HOST']]);

$host=$_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$full_domain = $protocol."://" .$host."/main_pickle/api/byte/get_client_icon.php?file=";

if($_SERVER['REQUEST_METHOD']==="POST"){

    if(getBearerToken()){

$courts=null;
$created = true;

if(!$existing_domain){
    header('HTTP/1.1 404 Not Found');
    echo json_encode(["Message" => "The domain is not registered yet."]);
    exit;
}
if($existing_domain){
$databaseName = $existing_domain->getDatabasename();
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$court_repository = $entityManager->getRepository(ClientDb\Process\court::class);




    $existing_court = $court_repository->findOneBy(['court' => $input['court']]);

    if($existing_court){

     if(count($existing_court->getSchedule())===0){

      $timezone = new DateTimeZone('Asia/Manila');
      $schedule_date = DateTime::createFromFormat('n-j-Y',$input['date'], $timezone);
      $new_schedule = new ClientDb\Process\schedule;
      $new_schedule->setCourtdate($schedule_date);
      $new_schedule->setHoliday(false);
      $new_data = new ClientDb\Process\data;
      $new_data->setDatastatus(5);
      $new_data->setRemark($input['remark']);
      $new_data->setSlotrowoccupied($input['slot_row_occupied']);
      $new_data->setSlotcolumnoccupied(0);
      $new_data->setSlotno($input['slot_no']);
      $new_data->setMember(0);
      $new_data->setNonmember(0);
      $new_data->setPlayer(0);
      $timezone = new DateTimeZone('Asia/Manila');
      $created_date = new DateTime('now', $timezone);
      $new_data->setDatecreated($created_date);
      $new_data->setCreatedby(json_decode(getBearerToken(),true)['user_id']);
      $entityManager->persist($new_data);
      $entityManager->flush();
      $new_schedule->setData($new_data);

      $entityManager->persist($new_schedule);
      $entityManager->flush();
      $existing_court->setSchedule($new_schedule);
      $entityManager->flush();
      $created = true;
     }else{

    $selected_schedule = null;
     foreach($existing_court->getSchedule() as $schedule){

     $timezone = new DateTimeZone('Asia/Manila');
     $inputDate = DateTime::createFromFormat('n-j-Y',$input['date'], $timezone);
     $holidayDateTime = $schedule->getCourtdate();

   if ($holidayDateTime->format('Y-m-d') === $inputDate->format('Y-m-d')) {
      $selected_schedule=$schedule;

      }

    }


if($selected_schedule){
  
if(count($existing_domain->getSlot())<$input['slot_row_occupied']){
$created = false;
}else if(count($existing_domain->getSlot())-$input['slot_no']+1<$input['slot_row_occupied']){
$created = false;
}

foreach ($selected_schedule->getData() as $slot) {
if($slot->getSlotno()===$input['slot_no']){
$created = false;
}
else{
if($slot->getSlotno()>$input['slot_no']){
       if($slot->getSlotno()-$input['slot_no']<$input['slot_row_occupied']){
       $created = false;
       }
       }else{
        if($slot->getSlotrowoccupied()===$input['slot_no']){
        $created = false;
        }else{
        if($slot->getSlotrowoccupied()>$input['slot_no']){
        $created = false;
        }else{
        if(count($existing_domain->getSlot())<$input['slot_row_occupied']){
        $created = false;
        }else{
        if(count($existing_domain->getSlot())-$input['slot_no']+1<$input['slot_row_occupied']){
        $created = false;
                   }

             }

         }
       }

    }
  }
}




if($created){

      $new_data = new ClientDb\Process\data;
      $new_data->setDatastatus(5);
      $new_data->setRemark($input['remark']);
      $new_data->setSlotrowoccupied($input['slot_row_occupied']);
      $new_data->setSlotcolumnoccupied(0);
      $new_data->setSlotno($input['slot_no']);
      $new_data->setMember(0);
      $new_data->setNonmember(0);
      $new_data->setPlayer(0);
      $timezone = new DateTimeZone('Asia/Manila');
      $created_date = new DateTime('now', $timezone);
      $new_data->setDatecreated($created_date);
      $new_data->setCreatedby(json_decode(getBearerToken(),true)['user_id']);
      $entityManager->persist($new_data);
      $entityManager->flush();
      $selected_schedule->setData($new_data);
      $entityManager->flush();
}

}else{

if(count($existing_domain->getSlot())<$input['slot_row_occupied']){
$created = false;
}
if($created){
    $new_court = new ClientDb\Process\court;
    $new_court->setCourt($input['court']);
      $timezone = new DateTimeZone('Asia/Manila');
      $schedule_date = DateTime::createFromFormat('n-j-Y',$input['date'], $timezone);
      $new_schedule = new ClientDb\Process\schedule;
      $new_schedule->setCourtdate($schedule_date);
      $new_schedule->setHoliday(false);

      $new_data = new ClientDb\Process\data;
      $new_data->setDatastatus(5);
      $new_data->setRemark($input['remark']);
      $new_data->setSlotrowoccupied($input['slot_row_occupied']);
      $new_data->setSlotcolumnoccupied(0);
      $new_data->setSlotno($input['slot_no']);
      $new_data->setMember(0);
      $new_data->setNonmember(0);
      $new_data->setPlayer(0);
      $timezone = new DateTimeZone('Asia/Manila');
      $created_date = new DateTime('now', $timezone);
      $new_data->setDatecreated($created_date);
      $new_data->setCreatedby(json_decode(getBearerToken(),true)['user_id']);
      $entityManager->persist($new_data);
      $entityManager->flush();
      $new_schedule->setData($new_data);
      $entityManager->persist($new_schedule);
      $entityManager->flush();
      $existing_court->setSchedule($new_schedule);
      $entityManager->flush();
    $entityManager->persist($new_court);
    $entityManager->flush();
   }
}

}

    }else{
    $new_court = new ClientDb\Process\court;
    $new_court->setCourt($input['court']);
      $timezone = new DateTimeZone('Asia/Manila');
      $schedule_date = DateTime::createFromFormat('n-j-Y',$input['date'], $timezone);
      $new_schedule = new ClientDb\Process\schedule;
      $new_schedule->setCourtdate($schedule_date);
      $new_schedule->setHoliday(false);

      $new_data = new ClientDb\Process\data;
      $new_data->setDatastatus(5);
      $new_data->setRemark($input['remark']);
      $new_data->setSlotrowoccupied($input['slot_row_occupied']);
      $new_data->setSlotcolumnoccupied(0);
      $new_data->setSlotno($input['slot_no']);
      $new_data->setMember(0);
      $new_data->setNonmember(0);
      $new_data->setPlayer(0);
      $timezone = new DateTimeZone('Asia/Manila');
      $created_date = new DateTime('now', $timezone);
      $new_data->setDatecreated($created_date);
      $new_data->setCreatedby(json_decode(getBearerToken(),true)['user_id']);
      $entityManager->persist($new_data);
      $entityManager->flush();
      $new_schedule->setData($new_data);

      $entityManager->persist($new_schedule);
      $entityManager->flush();
      $new_court->setSchedule($new_schedule);
      $entityManager->flush();
    $entityManager->persist($new_court);
    $entityManager->flush();
    $created=true;
  }

}

if($created){
       header('HTTP/1.1 201 Created');
       echo json_encode(['Message'=>"Block set!"]);
}else{
      header('HTTP/1.1 409 Conflict');
      echo json_encode(["Message" => "Cannot occupied more than ".$input['slot_row_occupied']."!"]);
}

}


}else{
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}



