<?php
class field_loop {
    public function setLoopfield($entityManager,$processDb,$field,$task,$main_db){
        $collect_data = [];
        $new_field = new configuration_process\field;
        $field = $entityManager->find(configuration_process\field::class,$field->getId());
        if (!$field->getTaskfield()->isEmpty()) {
            $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
            $new_field->setQuestion($field->getQuestion());
            $new_field->setAnswer($field->getAnswer());
            $new_field->setFormula($field->getFormula());
            $new_field->setRowno($field->getRowno());
            $new_field->setColno($field->getColno());
            $new_field->setRowoccupied($field->getRowoccupied());
            $new_field->setColoccupied($field->getColoccupied());
            $new_field->setActivatestyle($field->getActivatestyle());
            $new_field->setStyle($field->getStyle());
            $new_field->setFieldtype($type->getId());
            if($main_db){
                $task = $entityManager->find(configuration_process\task::class,$task->getId());
                $entityManager->persist($new_field);
                $entityManager->flush();
                $task->setTaskfield($new_field);
                $entityManager->flush();
            }else{
                $task2 = $processDb->find(configuration_process\task::class,$task->getId());
                $processDb->persist($new_field);  
                $processDb->flush();
                $task2->setTaskfield($new_field);
                $processDb->flush();
            }
            $new_type = $entityManager->find(configuration_process\field_type::class, $new_field->getFieldtype());
            if(
                $new_type->getDescription()==="CONDITION"|| 
                $new_type->getDescription()==="CHOICE" || 
                $new_type->getDescription()==="MATH" || 
                $new_type->getDescription() === "NUMBER" || 
                $new_type->getDescription() === "DECIMAL" || 
                $new_type->getDescription() ==="DESCRIPTION")
            {
                array_push($collect_data,[$field->getId()=>$new_field->getId()]);
            }
            return $collect_data;
            // $collect_data =   $this->setLinkloopfield($entityManager,$processDb,$new_field->getId(),$field->getId(),$collect_data,$main_db);
        } else {
            $origin = $field->getBidirectional()->first()->getId();
            $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype()->getId());
            $new_field->setQuestion($field->getQuestion());
            $new_field->setAnswer($field->getAnswer());
            $new_field->setFormula($field->getFormula());
            $new_field->setRowno($field->getRowno());
            $new_field->setColno($field->getColno());
            $new_field->setRowoccupied($field->getRowoccupied());
            $new_field->setColoccupied($field->getColoccupied());
            $new_field->setStyle($field->getStyle());
            $new_field->setActivatestyle($field->getActivatestyle());
            $new_field->setFieldtype($type->getId());
            if($main_db){
                $linking_field = $entityManager->find(configuration_process\field::class,$origin);
                $entityManager->persist($new_field);
                $entityManager->flush();
                $linking_field->setFieldlink($new_field);
                $entityManager->flush();
            }else{
                $linking_field2 = $processDb->find(configuration_process\field::class,$origin);
                $processDb->persist($new_field);
                $processDb->flush();
                $linking_field2->setFieldlink($new_field);
                $processDb->flush();
            }
                $new_type = $entityManager->find(configuration_process\field_type::class, $new_field->getFieldtype());
            if(
                $new_type->getDescription()==="CONDITION"||
                $new_type->getDescription()==="CHOICE" || 
                $new_type->getDescription()==="MATH" || 
                $new_type->getDescription() === "NUMBER" || 
                $new_type->getDescription() === "DECIMAL" || 
                $new_type->getDescription() ==="DESCRIPTION")
            {
                array_push($collect_data,[$field->getId()=>$new_field->getId()]);
            }
            // $collect_data =   $this->setLinkloopfield($entityManager,$processDb,$new_field->getId(),$field->getId(),$collect_data,$main_db);

            return $collect_data;
        }
    }
    // public function setLinkloopfield($entityManager, $processDb, $new_field, $old_field, $collect_data,$main_db) {
    //     $old_field = $entityManager->find(configuration_process\field::class, $old_field);
    //     // foreach ($old_field->getFieldlink() as $link) {
    //     //     $link_new_field = new configuration_process\field();
    //     //     $type = $entityManager->find(configuration_process\field_type::class, $link->getFieldtype());
    //     //     $link_new_field->setQuestion($link->getQuestion());
    //     //     $link_new_field->setAnswer($link->getAnswer());
    //     //     $link_new_field->setFormula($link->getFormula());
    //     //     $new_field->setRowno($field->getRowno());
    //     //     $new_field->setColno($field->getColno());
    //     //     $new_field->setRowoccupied($field->getRowoccupied());
    //     //     $new_field->setColoccupied($field->getColoccupied());
    //     //     $link_new_field->setFieldtype($type->getId());
    //     //     if($main_db){
    //     //         $new_field = $entityManager->find(configuration_process\field::class, $new_field);
    //     //         $entityManager->persist($link_new_field);
    //     //         $entityManager->flush();       
    //     //         $new_field->setFieldlink($link_new_field);
    //     //         $entityManager->flush();
    //     //     }else{
    //     //         $new_field2 = $processDb->find(configuration_process\field::class, $new_field);
    //     //         $processDb->persist($link_new_field);
    //     //         $processDb->flush();       
    //     //         $new_field2->setFieldlink($link_new_field);
    //     //         $processDb->flush();
    //     //     }
    //     //     $new_type = $entityManager->find(configuration_process\field_type::class,$link->getFieldtype());
    //     //     if(
    //     //         $new_type->getDescription()==="CONDITION"||
    //     //         $new_type->getDescription()==="CHOICE" || 
    //     //         $new_type->getDescription()==="MATH" || 
    //     //         $new_type->getDescription() === "NUMBER" || 
    //     //         $new_type->getDescription() === "DECIMAL" || 
    //     //         $new_type->getDescription() ==="DESCRIPTION")
    //     //     {
    //     //         $collect_data[] = [$link->getId() => $link_new_field->getId()];
    //     //     }
    //     //     $collect_data = $this->setLinkloopfield($entityManager,$processDb, $link_new_field->getId(), $link->getId(), $collect_data,$main_db);
    //     // }
    //     return $collect_data;
    // }
}