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
$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {
        try {
            $token = json_decode(getBearerToken(), true);
            $database = $token['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();

            $preemptive_repository = $processDb->getRepository(configuration_process\preemptive::class);
            $startDate = $input['start_date'] ?? null;
            $endDate = $input['end_date'] ?? null;
            $userId = $input['user_id'] ?? null;
            $storeId = $input['store_id'] ?? null;
            $queryBuilder = $preemptive_repository->createQueryBuilder('p');
            if (!empty($startDate) && !empty($endDate)) {

                $queryBuilder
                    ->where('p.date_planned BETWEEN :start AND :end')
                    ->setParameter('start', $startDate)
                    ->setParameter('end', $endDate);
            } elseif (!empty($startDate)) {


                $queryBuilder
                    ->where('p.date_planned >= :start')
                    ->setParameter('start', $startDate);
            } elseif (!empty($endDate)) {

                $queryBuilder
                    ->where('p.date_planned <= :end')
                    ->setParameter('end', $endDate);
            }

            if (!empty($userId)) {
                $queryBuilder->andWhere('p.user_id = :userId')
                    ->setParameter('userId', $userId);
            }

            if (!empty($storeId)) {
                $queryBuilder->andWhere('p.store_id = :storeId')
                    ->setParameter('storeId', $storeId);
            }

            $queryBuilder->andWhere('p.remove IS NULL OR p.remove = false');
            $results = $queryBuilder->getQuery()->getResult();
            $preemptive_list = [];
            foreach ($results as $result) {
                $status_color = 'blue';

                if ($result->getItinerary()) {
                    $itinerary = $processDb->find(configuration_process\itinerary::class, $result->getItinerary());
                    $timezone = new DateTimeZone('Asia/Manila');
                    $now = new DateTime('now', $timezone);
                    $plannedDate = $result->getDateplanned();
                    $plannedDate->setTimezone($timezone);
                    if ($now->format('Y-m-d') === $plannedDate->format('Y-m-d')) {
                        if ($itinerary && $itinerary->getCheckin() && $itinerary->getCheckout()) {
                            $status_color = 'green';
                        } else if ($itinerary && !$itinerary->getCheckin() && !$itinerary->getCheckout()) {
                            $status_color = 'yellow';
                        } else {
                            $status_color = 'red';
                        }
                    } else {
                        $status_color = 'red';
                    }
                }
                if ($result->getRemove() == false || $result->getRemove() == null) {
                    $user = $entityManager->find(configuration\user::class, $result->getUser());
                    $created_by = $entityManager->find(configuration\user::class, $result->getCreatedby());
                    $store = $entityManager->find(configuration\store::class, $result->getStore());
                    $preemptive_list[] = [
                        'id' => $result->getId(),
                        'user' => $user->getFirstname() . ' ' . $user->getLastname(),
                        'store' => $store->getOutletname(),
                        'itinerary' => $result->getItinerary(),
                        'date_created' => $result->getDatecreated()->format('Y-m-d'),
                        'date_planned' => $result->getDateplanned()->format('Y-m-d'),
                        'date_actual' =>  $result->getDateactual()->format('Y-m-d'),
                        'remark' => $result->getRemark(),
                        'created_by' => $created_by->getFirstname() . ' ' . $created_by->getLastname(),
                        'status_color' => $status_color

                    ];
                }
            }

            header('HTTP/1.1 200 OK');
            echo json_encode($preemptive_list);
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
