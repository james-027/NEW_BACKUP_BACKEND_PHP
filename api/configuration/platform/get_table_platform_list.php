<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
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
        $origin = new configuration\origin;
        $table_platforms = $entityManager->getRepository(configuration_process\table_platform::class)->findAll();
        $platform_ids = [];

    foreach ($table_platforms as $table_platform) {
        $platform_ids[] = $table_platform->getPlatform()->getId(); 
    }
        $searchTerm = isset($input['search']) ? trim($input['search']) : '';

        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('p')  
                ->from(configuration_process\platform::class, 'p')  
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(p.description)', ':search'),
                
                ))
                ->andWhere($queryBuilder->expr()->in('p.id', ':platform_ids')) 
                ->setParameter('platform_ids', $platform_ids)  
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $platforms = $queryBuilder->getQuery()->getResult(); 
            $platform_list = [];
           
            foreach ($platforms as $platform) {
            $repository = $entityManager->getRepository(configuration_process\table_platform::class);
            $table_platform = $repository->findOneBy(['platform_id' => $platform->getId()]);
                 $sub_list = [];
                foreach($platform->getPlatformlink() as $link){
                     array_push($sub_list,[
                    'value'=>$link->getId(),
                    'label'=>$link->getDescription(),
                ]);
                }
                $path = $entityManager->find(configuration\path::class, $platform->getPath());
                array_push($platform_list,[
                    'id'=>$platform->getId(),
                    "table_platform_id"=>$table_platform->getId(),
                    'description'=>$platform->getDescription(),
                    'picture'=>$origin->getOrigin($path->getDescription(),$platform->getIcon()),
                    'sub_platform'=>$sub_list
                ]);
            }
            
        
            header('HTTP/1.1 200 OK');
            echo json_encode($platform_list);
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

