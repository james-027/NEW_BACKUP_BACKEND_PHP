<?php

class change_math{
 public function change_math($id,$entityManager){
           $field = $entityManager->find(configuration_process\field::class,$id);
         if($field->getFieldtype()==13){
           if(json_decode($field->getFormula(),true)['memory']===true){
             $id_array = explode(',',json_decode($field->getFormula(),true)['memory_id']);
              foreach ($id_array as $check_id) {
                 $field = $entityManager->find(configuration_process\field::class,$check_id);
                 $formula = json_decode($field->getFormula(), true);
                 $data = $formula['choices'];
                 foreach($data as $value){
                    $data[0]['value'] = $value['value'].','.$id;
                  }
                   $formula['choices'][0]=$data[0];
                   $field->setFormula(json_encode($formula));
              }
           }
         }
        }

}