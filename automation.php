<?php
// automation.php
set_time_limit(0); // Allow the script to run indefinitely

function date_comparing($rentaltime, $rental) {
    require "bootstrap.php";   
    $thresholdHours = 0;
    $thresholdMilliseconds = $thresholdHours * 60 * 60 * 1000; // Convert 3 hours to milliseconds

    // Decode the rental time provided
    $jsonString = json_encode($rentaltime);
    $data = json_decode($jsonString, true);
    $time1 = $data['date'];

    // Set the default timezone and get the current time including microseconds
    date_default_timezone_set('Asia/Manila');
    $time2 = (new DateTime())->format('Y-m-d H:i:s.u');

    // Get the timestamps
    $timestamp1 = strtotime($time1) * 1000; // Convert to milliseconds
    $timestamp2 = (float)(new DateTime($time2))->format('U.u') * 1000; // Convert to milliseconds

    // Calculating the time difference
    $timeDifference = $timestamp2 - $timestamp1;

    // Check if the rental time is valid (not in the future)
    if ($timeDifference < 0) {
     //   echo "The rental time cannot be in the future.";
        return false;
    } 

    // Check if the rental time is within the threshold
    if ($timeDifference <= $thresholdMilliseconds) {
      //  echo "This is within 3 hours.";
        return true;
    } else {
      //  echo "This is not within 3 hours.";
        return false;
    }
}


while (true) {
    require "bootstrap.php";   
  
    $currentDate = new DateTime();
$startDate = clone $currentDate;
$startDate;
$endDate = clone $startDate;
$endDate->modify('+2 weeks');

echo json_encode($startDate);


    $user_rental = $entityManager->getRepository('Court')->findAll();
    $rental_list = [];


    foreach ($user_rental as $rental) {
        $preferredDate = $rental->getPreferredDate();
        if (($startDate === null || $preferredDate >= $startDate) && 
            ($endDate === null || $preferredDate <= $endDate)) {

              
      
        if($rental->getSlot1()!=null){ 
            if($rental->getSlot1()->getStatus()->id()=="1"){
                if(date_comparing($rental->getSlot1()->getRemainingTime(),$rental)==false){
                    $save_for_deletion = $rental->getSlot1()->id();
                   $rental->setSlot1(null);
                   $entityManager->flush();  
                    $court_data = $entityManager->find(Courtdata::class,$save_for_deletion);
                    $user_court_data = $entityManager->getRepository('User')->findAll();
                    foreach($user_court_data as $user){
                        $user->removeCourtData($user->getCourtData(),$court_data);
                        $entityManager->flush();  
                    }
                    $entityManager->remove($court_data);  
                    $entityManager->flush();  
                }
            }
        }
        if($rental->getSlot2()!=null){ 
            if($rental->getSlot2()->getStatus()->id()=="1"){
                if(date_comparing($rental->getSlot2()->getRemainingTime(),$rental)==false){
                    $save_for_deletion = $rental->getSlot2()->id();
                   $rental->setSlot2(null);
                   $entityManager->flush();  
                    $court_data = $entityManager->find(Courtdata::class,$save_for_deletion);
                    $user_court_data = $entityManager->getRepository('User')->findAll();
                    foreach($user_court_data as $user){
                        $user->removeCourtData($user->getCourtData(),$court_data);
                        $entityManager->flush();  
                    }
                    $entityManager->remove($court_data);  
                    $entityManager->flush();  
                }
            }
        }
        if($rental->getSlot3()!=null){ 
            if($rental->getSlot3()->getStatus()->id()=="1"){
                if(date_comparing($rental->getSlot3()->getRemainingTime(),$rental)==false){
                    $save_for_deletion = $rental->getSlot3()->id();
                   $rental->setSlot3(null);
                   $entityManager->flush();  
                    $court_data = $entityManager->find(Courtdata::class,$save_for_deletion);
                    $user_court_data = $entityManager->getRepository('User')->findAll();
                    foreach($user_court_data as $user){
                        $user->removeCourtData($user->getCourtData(),$court_data);
                        $entityManager->flush();  
                    }
                    $entityManager->remove($court_data);  
                    $entityManager->flush();  
                }
            }
        }
        if($rental->getSlot4()!=null){ 
            if($rental->getSlot4()->getStatus()->id()=="1"){
                if(date_comparing($rental->getSlot4()->getRemainingTime(),$rental)==false){
                    $save_for_deletion = $rental->getSlot4()->id();
                   $rental->setSlot4(null);
                   $entityManager->flush();  
                    $court_data = $entityManager->find(Courtdata::class,$save_for_deletion);
                    $user_court_data = $entityManager->getRepository('User')->findAll();
                    foreach($user_court_data as $user){
                        $user->removeCourtData($user->getCourtData(),$court_data);
                        $entityManager->flush();  
                    }
                    $entityManager->remove($court_data);  
                    $entityManager->flush();  
                }
            }
        }
        if($rental->getSlot5()!=null){ 
            if($rental->getSlot5()->getStatus()->id()=="1"){
                if(date_comparing($rental->getSlot5()->getRemainingTime(),$rental)==false){
                    $save_for_deletion = $rental->getSlot5()->id();
                   $rental->setSlot5(null);
                   $entityManager->flush();  
                    $court_data = $entityManager->find(Courtdata::class,$save_for_deletion);
                    $user_court_data = $entityManager->getRepository('User')->findAll();
                    foreach($user_court_data as $user){
                        $user->removeCourtData($user->getCourtData(),$court_data);
                        $entityManager->flush();  
                    }
                    $entityManager->remove($court_data);  
                    $entityManager->flush();  
                }
            }
        }
  
        }
    }
    sleep(2); // Wait for 2 seconds
}
