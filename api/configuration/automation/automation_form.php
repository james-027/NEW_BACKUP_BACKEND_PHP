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


$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);


while(true)
{
    $flag = "on";
    if($flag === "on") {
        $flag = "off";
        $automation_form = $entityManager->getRepository(configuration_process\automation_form::class);
        $firstRecord = $automation_form->findOneBy([], ['id' => 'ASC']);
        if ($firstRecord) {
            if($firstRecord->getItinerary()){   
                echo 'available';
            }else{
                $form = $entityManager->find(configuration_process\form::class, $firstRecord->getForm());
                echo $form->getId();
                // $user = $entityManager->find(configuration\user::class, $firstRecord->getCreatedby()->getId());
                // $itinerary = $entityManager->find(configuration_process\itinerary::class, $firstRecord->getItinerary()->getId());
                // $new_form = new configuration_process\automation_form;
                // $new_form->setForm($form);
                // $new_form->setCreatedby($user);
                // $new_form->setItinerary($itinerary);
                // $entityManager->persist($new_form);
                // $entityManager->flush();
                
            }
        }
        $flag = "on";
        echo "Form Created!\n";
    }
    sleep(2);
}

?>
