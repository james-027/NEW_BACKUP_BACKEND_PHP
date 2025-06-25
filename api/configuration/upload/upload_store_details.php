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

$file_directory = dirname(dirname(dirname(dirname(__DIR__))));
$fileName = $_GET['file'] ?? null; 
$filePathParam = $_GET['path'] ?? ''; 

use PhpOffice\PhpSpreadsheet\IOFactory;
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

function cleanHeaders($headers) {
    return array_values(array_filter($headers, function($header) {
        return !is_null($header) && $header !== '';
    }));
}
function cleanHeadersWithTrim($headers) {
    return array_map(function($header) {
        return trim(str_replace(["\n", "\r"], '', $header)); 
    }, cleanHeaders($headers));
}

 function createUser($entityManager,$username,$type,$path) {

    $userRepository = $entityManager->getRepository(configuration\user::class);
    $user = $userRepository->findOneBy(['username' => $username]);
    if (!$user) {
        $user = new configuration\user();
        $user->setUsername($username);
        $user->setPassword('123456');
        $path = $entityManager->find(configuration\path::class, $path);
        $user->setPath($path);
        $user_type = $entityManager->find(configuration_process\user_type::class, $type);
        $user->setUsertype($user_type);
        $user->setActivate(true);
        $user->setPicture("profile.png");

        $entityManager->persist($user);
    }
    return $user;
}


function createUserRegion($entityManager,$username,$type,$path) {

    $userRepository = $entityManager->getRepository(configuration\user::class);
    $user = $userRepository->findOneBy(['username' => $username]);
    if (!$user) {
        $user = new configuration\user();
        $user->setUsername($username);
        $user->setFirstname($username);
        $user->setPassword('123456');
        $path = $entityManager->find(configuration\path::class, $path);
        $user->setPath($path);
        $user_type = $entityManager->find(configuration_process\user_type::class, $type);
        $user->setUsertype($user_type);
        $user->setActivate(true);
        $user->setPicture("profile.png");

        $entityManager->persist($user);
    }
    return $user;
}

while(true)
{
    $flag = "on";
    if($flag === "on") {
        $flag = "off";
        $automation_store = $entityManager->getRepository(configuration\automation_store::class);
        $firstRecord = $automation_store->findOneBy([], ['id' => 'ASC']);
        if ($firstRecord) {
            $targetDirectory = $file_directory . "/digital_workspace_file/file/" . $firstRecord->getPath()->getDescription() . '/' . $firstRecord->getFile();
            if (file_exists($targetDirectory)) {
                try {
                    $spreadsheet = IOFactory::load($targetDirectory);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();
                    $cleanHeaders = cleanHeaders(array_shift($data));
                    $headers = cleanHeadersWithTrim($cleanHeaders);
                    foreach ($data as $row) {
                        $row = array_map(function($value) {
                            return $value === null ? '' : $value; 
                        }, $row);
                        $row = array_pad($row, count($headers), '');
                        $row = array_slice($row, 0, count($headers));
                        if (count($row) === count($headers)) {
                            $combinedRow = array_combine($headers, $row);
                            $coordinatesArray = explode(',', $combinedRow['Coordinates'] ?? '');
                            $longitude = isset($coordinatesArray[0]) ? trim($coordinatesArray[0]) : '';
                            $latitude = isset($coordinatesArray[1]) ? trim($coordinatesArray[1]) : '';
                            if (!is_numeric($latitude) || !is_numeric($longitude) || $latitude == '#N/A' || $longitude == '#N/A' || empty($latitude) || empty($longitude)) {
                                $latitude = 0.0;
                                $longitude = 0.0;
                            } else {
                                $latitude = (float) $latitude;
                                $longitude = (float) $longitude;
                            }
                            $categoryRepository = $entityManager->getRepository(configuration\category::class);
                            if (!empty($combinedRow['REGION']) && !empty($combinedRow['BRANCH/ BUSINESS CENTER'])) {
                                $userRepository = $entityManager->getRepository(configuration\user::class);
                                $user_business_center = $userRepository->findOneBy(['username' => $combinedRow['BRANCH/ BUSINESS CENTER']]);
                                $user_region = $userRepository->findOneBy(['username' => $combinedRow['REGION']]);
                                $user_store = $userRepository->findOneBy(['username' => $combinedRow['OUTLET CODE(BAVI)']]);
                                $region = $categoryRepository->findOneBy(['description' => $combinedRow['REGION']]);
                                if ($region) {
                                    $business_center = $categoryRepository->findOneBy(['description' => $combinedRow['BRANCH/ BUSINESS CENTER']]);
                                    if (!$user_business_center) {
                                        $new_user_business_center = createUserRegion($entityManager,$combinedRow['BRANCH/ BUSINESS CENTER'], 3, 5);
                                        if (!$business_center) {
                                            $new_business_center = new configuration\category;
                                            $new_business_center->setDescription($combinedRow['BRANCH/ BUSINESS CENTER']);
                                            // $entityManager->persist($new_business_center);
                                            // $entityManager->flush();
                                            $user_region->setUserlink($new_user_business_center);
                                            $user_business_center = $new_user_business_center;
                                        }
                                        $entityManager->persist($user_business_center);
                                    }else {
                                    $existingLinks = $user_region->getUserlink(); 
                                    if (!$existingLinks->contains($user_business_center)) {
                                        $user_region->setUserlink($user_business_center); 
                                    }
                                    }
                                } else {
                                    $new_region = new configuration\category;
                                    $region_user = createUserRegion($entityManager,$combinedRow['REGION'], 4, 5);   
                                    $category = $categoryRepository->findOneBy(['description' => $combinedRow['REGION']]);                 
                                    if (!$user_business_center) {
                                        $new_user_business_center = createUserRegion($entityManager,$combinedRow['BRANCH/ BUSINESS CENTER'], 3, 5);
                                        $new_business_center = new configuration\category;
                                        $new_business_center->setDescription($combinedRow['BRANCH/ BUSINESS CENTER']);
                                        // $entityManager->persist($new_business_center);
                                        // $entityManager->flush();
                                        $region_user->setUserlink($new_user_business_center);
                                        $user_business_center = $new_user_business_center;
                                    }
                                }
                                $storeRepository = $entityManager->getRepository(configuration\store::class);
                                $store = $storeRepository->findOneBy(['outlet_code' => $combinedRow['OUTLET CODE(BAVI)']]);
                                    if (!$store) {
                                        $store = new configuration\store();
                                        $store->setOutletcode($combinedRow['OUTLET CODE(BAVI)']);
                                        $created_by = $entityManager->find(configuration\user::class, $firstRecord->getCreatedby()->getId());
                                        $store->setCreatedby($created_by);
                                        $store->setOutletname($combinedRow['OUTLET NAME']);
                                        $store->setTown($combinedRow['TOWN GROUP']);
                                        $store->setZipcode($combinedRow['ZIP CODE']);
                                        $store->setAddress($combinedRow['ADDRESS']);
                                        $store->setLatitude($latitude);
                                        $store->setLongitude($longitude);
                                        $store->setDistance($combinedRow['distance']);
                                        $entityManager->persist($store);
                                    }else{
                                        if ($store->getOutletname() !== $combinedRow['OUTLET NAME']) {
                                            $store->setOutletname($combinedRow['OUTLET NAME']);
                                        }
                                        if ($store->getTown() !== $combinedRow['TOWN GROUP']) {
                                            $store->setTown($combinedRow['TOWN GROUP']);
                                        }
                                        if ($store->getZipcode() !== $combinedRow['ZIP CODE']) {
                                            $store->setZipcode($combinedRow['ZIP CODE']);
                                        }
                                        if ($store->getAddress() !== $combinedRow['ADDRESS']) {
                                            $store->setAddress($combinedRow['ADDRESS']);
                                        }
                                        if ($store->getLatitude() != $latitude) {
                                            $store->setLatitude($latitude);
                                        }
                                        if ($store->getLongitude() != $longitude) {
                                            $store->setLongitude($longitude);
                                        }
                                        if ($store->getDistance() != $combinedRow['distance']) {
                                            $store->setDistance($combinedRow['distance']);
                                        }
                                    }
                                    if (!$user_store) {
                                        $new_user_store = createUser($entityManager,$combinedRow['OUTLET CODE(BAVI)'], 2, 5);
                                        $new_user_store->setStore($store);
                                        $user_business_center->setUserlink($new_user_store);
                                    }else{
                                            $existingLinks = $user_business_center->getUserlink(); 
                                        if (!$existingLinks->contains($user_store)) {
                                            $user_business_center->setUserlink($user_store); 
                                        }
                                    }
                                    
                                    $user_business_center->setUserstore($store);
                                $entityManager->flush();
                            }
                        }
                    }
                    $entityManager->clear();
                    $entityManager->flush();    
                } catch (Exception $e) {
                    echo json_encode(["Message" => $e->getMessage()]);
                }
            } else {
                echo json_encode(["Message" => "File not found.", "path" => htmlspecialchars($targetDirectory)]);
            }
            try {
                $entityManager->remove($firstRecord);
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $managedEntity = $entityManager->getRepository(configuration\automation_store::class)->find($firstRecord->getId());
                if ($managedEntity) {
                    $entityManager->remove($managedEntity);
                } else {
                    echo "Entity not found, cannot remove.";
                }
            }
            $entityManager->flush();
        }
        $flag = "on";
        echo "Uploaded Successfully!\n";
    }
    sleep(2);
}

?>
