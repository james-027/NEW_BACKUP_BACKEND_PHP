<?php 

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Setup;



require_once "vendor/autoload.php"; 
/*CONFIGURATION*/
require __DIR__ . '/src/configuration/user.php';
require __DIR__ . '/src/configuration/store.php';
require __DIR__ . '/src/configuration/category.php';
require __DIR__ . '/src/configuration/category_type.php';
require __DIR__ . '/src/configuration/automation_store.php';
require __DIR__ . '/api/byte/origin.php';
require __DIR__ . '/api/configuration/change/formula_change.php';
require __DIR__ . '/api/configuration/change/change_math.php';
require __DIR__ . '/api/configuration/replicate/field_loop.php';
require __DIR__ . '/api/configuration/replicate/form_loop.php';
require __DIR__ . '/api/remove/remove_field.php';
require __DIR__ . '/api/remove/remove_platform.php';
require __DIR__ . '/api/remove/remove_category.php';
require __DIR__ . '/api/configuration/collect/formula_collect.php';
/*CONFIGURATION_PROCESS*/
require __DIR__ . '/src/configuration_process/user_type.php';
require __DIR__ . '/src/configuration_process/platform.php';
require __DIR__ . '/src/configuration_process/form.php';
require __DIR__ . '/src/configuration_process/connection_form.php';
require __DIR__ . '/src/configuration_process/justification_form.php';
require __DIR__ . '/src/configuration_process/table_form.php';
require __DIR__ . '/src/configuration_process/itinerary_type.php';
require __DIR__ . '/src/configuration_process/connection_itinerary.php';
require __DIR__ . '/src/configuration_process/justification_itinerary.php';
require __DIR__ . '/src/configuration_process/automation_form_publishing.php';
require __DIR__ . '/src/configuration_process/automation_form.php';
require __DIR__ . '/src/configuration_process/automation_itinerary.php';
require __DIR__ . '/src/configuration_process/field_type.php';
require __DIR__ . '/src/configuration_process/field.php';
require __DIR__ . '/src/configuration_process/form_type.php';
require __DIR__ . '/src/configuration_process/plot.php';
require __DIR__ . '/src/configuration_process/react_type.php';
require __DIR__ . '/src/configuration_process/report_type.php';
require __DIR__ . '/src/configuration_process/report.php';
require __DIR__ . '/src/configuration_process/status.php';
require __DIR__ . '/src/configuration_process/task.php';
require __DIR__ . '/src/configuration_process/validation.php';
require __DIR__ . '/src/configuration_process/table_platform.php';
require __DIR__ . '/src/configuration_process/reform.php';
require __DIR__ . '/src/configuration_process/track_itinerary.php';
require __DIR__ . '/src/configuration_process/preventive.php';
/*PROCESS*/
require __DIR__ . '/src/process/user.php';
require __DIR__ . '/src/process/store.php';
require __DIR__ . '/src/process/schedule.php';
require __DIR__ . '/src/process/user_assign.php';
require __DIR__ . '/src/process/react_post.php';
require __DIR__ . '/src/process/react_content.php';
require __DIR__ . '/src/process/react_comment_post.php';
require __DIR__ . '/src/process/react_comment_content.php';
require __DIR__ . '/src/process/post.php';
require __DIR__ . '/src/process/notification.php';
require __DIR__ . '/src/process/message.php';
require __DIR__ . '/src/process/justification_concern.php';
require __DIR__ . '/src/process/content.php';
require __DIR__ . '/src/process/concern.php';
require __DIR__ . '/src/process/comment_post.php';
require __DIR__ . '/src/process/comment_content.php';
require __DIR__ . '/src/process/chat.php';
/*TEMPERATURE*/
require __DIR__ . '/src/temperature/oven.php';
require __DIR__ . '/src/temperature/type.php';
require __DIR__ . '/src/temperature/detail.php';
/*TOKEN*/
require __DIR__ . '/api/security/token.php';
class DatabaseConnection {
    private $connectionParams = [
        'user' => 'main',
        'password' => 'nnsnqh9jha5lmyKBu8V3sITrDF3rt2PUGYq3qjDgXpDu8RHXPo', 
        'host' => '127.0.0.1', 
        'port' => '3306',  
        'driver' => 'pdo_mysql', 
    ]; 
    private $entityManager;

    public function __construct(string $dbname) {
        $this->connectionParams['dbname'] = $dbname; 
        $proxyDir = __DIR__ . '/proxies';  
        if (!is_dir($proxyDir)) {
            mkdir($proxyDir, 0777, true);  
        }
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(
                __DIR__ . "/src/configuration",
                __DIR__ . "/src/configuration_process",
                __DIR__ . "/src/process",
                __DIR__ . "/src/temperature",
        ), 
            isDevMode: true
        );
        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace('DoctrineProxies');
        $connection = DriverManager::getConnection($this->connectionParams);
        $this->entityManager = new EntityManager($connection, $config);
    }
    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }
}
