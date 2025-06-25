<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
$parent_directory = dirname(dirname(dirname(__DIR__)));
require $parent_directory . '/vendor/autoload.php';
require $parent_directory . '/database.php';

$databaseName = "dws_db_" . $currentDateTime->format('Y');
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
while(true)
{
    $flag = "on";
    if($flag === "on") {
        $flag = "off";
        $automation_itinerary = $entityManager->getRepository(configuration_process\automation_itinerary::class);
        $firstRecord = $automation_itinerary->findBy([], ['id' => 'ASC']);
        if ($firstRecord) {
            foreach($firstRecord as $record){
                if(!$record->getProcess()){
                    $repository = $entityManager->getRepository(process\user::class);
                    $existingUser = $repository->findOneBy(['id' => $record->getCreatedby()]);
                    if($existingUser){
                        $user = $existingUser;
                    }else{
                        $user = new process\user;
                        $user->setId($record->getCreatedby());
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }
                $itinerary = new configuration_process\itinerary();
                $itinerary->setType($record->getItinerarytype());
                $itinerary->setSchedule($record->getSchedule());
                $itinerary->setStore($record->getStore());
                $itinerary->setDatecreated($record->getDatecreated());
                $itinerary->setCreatedby($record->getCreatedby());
                $entityManager->persist($itinerary);
                if($record->getJustification()!= ""){
                    $new_justification = new configuration_process\justification_itinerary();
                    $new_justification->setDateCreated($record->getDatecreated());
                    $new_justification->setCreatedby($user);
                    $new_justification->setDescription($record->getJustification());
                    $entityManager->persist($new_justification);
                    $itinerary->setItineraryjustification($new_justification);
                    }
                    $record->setProcess(1);
                    $user->setUseritinerarygenerator($itinerary);
                    $entityManager->flush();
            }
            }
        }
        $flag = "on";
        echo "Itinerary Created!\n";
    }
    sleep(2);
}

?>
