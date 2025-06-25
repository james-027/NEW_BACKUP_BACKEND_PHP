<?php

class form_loop {
    public function setFormloop($entityManager, $processDb, $form_id,$main_db)
    {
        $field_loop = new field_loop();
        $formula_change = new formula_change();
        $collect_data = [];
        $form = $entityManager->find(configuration_process\form::class, $form_id);
        $new_form = new configuration_process\form;
        $new_form->setTitle($form->getTitle());
        $new_form->setParentform($form->getId());
        $new_form->setRemark($form->getRemark());
        $new_form->setCreatedby($form->getCreatedby());
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime('now', $timezone);
        $new_form->setDatecreated($date);
        $new_form->setFormtype($form->getFormtype());
        $new_form->setVersion($form->getVersion());
        $new_form->setChance('0.0.0');
        if($main_db){
            $entityManager->persist($new_form);
            $entityManager->flush(); 
        }else{
            $processDb->persist($new_form);
            $processDb->flush();
        } 
        foreach ($form->getFormtask() as $task) {
            $new_task = new configuration_process\task;
            $new_task->setTitle($task->getTitle());
            $new_task->setDescription($task->getDescription());
            $new_task->setStatus($task->getStatus());
            $new_task->setSeries($task->getSeries());
            if($main_db){
                $entityManager->persist($new_task);
                $entityManager->flush(); 
                $new_form->setFormtask($new_task); 
                $entityManager->flush(); 
            }else{
                $processDb->persist($new_task);
                $processDb->flush();  
                $new_form->setFormtask($new_task); 
                $processDb->flush(); 
            }
            foreach($task->getTaskvalidation() as $validation){
                $new_validation = new configuration_process\validation();
                $new_validation->setValid($validation->getValid() ? $validation->getValid() : null);
                $new_validation->setCreatedby($validation->getCreatedby());
                $new_validation->setUsertype( $validation->getUsertype());
                if($main_db){
                    $entityManager->persist($new_validation);
                    $entityManager->flush(); 
                    $new_task->setTaskvalidation($new_validation);
                    $entityManager->flush();
                }else{
                    $processDb->persist($new_validation);
                    $processDb->flush(); 
                    $new_task->setTaskvalidation($new_validation);
                    $processDb->flush();
                }
            }
            foreach($task->getTaskfield() as $field){
                $setFieldLoop = $field_loop->setLoopfield($entityManager,$processDb,$field,$new_task,$main_db);
            }
        }
        return $new_form->getId();
    }
}
