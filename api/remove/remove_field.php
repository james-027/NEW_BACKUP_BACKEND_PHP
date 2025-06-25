<?php
class remove_field{
    public function setRemovefield($entityManager,$origin_id,$field_id,$task_id){
        $origin = $entityManager->find(configuration_process\field::class,$origin_id);
        if($origin){
            foreach($origin->getTaskfield() as $task){
                if($task->getId() === $task_id){
                    $task_id = $entityManager->find(configuration_process\task::class,$task_id);
                    $task_id->removeTaskfield($task_id->getTaskfield(),$origin);
                    $entityManager->flush();
                }
            }
        }
        $field = $entityManager->find(configuration_process\field::class,$field_id);
        if($field){
            foreach($field->getFieldlink() as $link){
                $link_field = $entityManager->find(configuration_process\field::class,$link->getId());
                if($field){
                    $this->setRemovefield($entityManager,$origin_id,$link->getId(),$task_id);
                }
                $entityManager->remove($link_field);
                $entityManager->flush();
            }
        }  
        try{
            $remove_origin= $entityManager->find(configuration_process\field::class,$origin_id);
            if ($remove_origin){
                $entityManager->remove($remove_origin); 
                $entityManager->flush();  
            }
        }catch(Exception $e){
        }
    }
}

