<?php


class change_formula{
     
     public function setChangeformula($entityManager,$field,$collect_data) {
    
            $field = $entityManager->find(configuration_process\field::class,$field);

             if($field->getFieldtype()===10){
                    $selectanswer = json_decode($field->getFormula(), true);

        if($selectanswer){
        foreach($selectanswer[0]['choices'] as $index => $divisor){
            $mapping = [];
            foreach ($collect_data as $item) {
                foreach ($item as $key => $value) {
                    $mapping[$key] = $value;
                }
            }
            $original_keys = explode(",", $selectanswer[0]['choices'][$index]['value']);
            $new_values = [];
            foreach ($original_keys as $key) {
                if (isset($mapping[$key])) {
                    $new_values[] = $mapping[$key];
                } else {
                    $new_values[] = $key;
                }
            }
            $new_string = implode(",", $new_values);
            $selectanswer[0]['choices'][$index]['value']=$new_string;
            $field->setFormula(json_encode($selectanswer));
            $entityManager->flush();
        }
        }
                        }
                        
                        else if($field->getFieldtype()===9){
                            $selectanswer = json_decode($field->getFormula(), true);
        $original_array =$selectanswer[0]['choices'];

        $mapping = [];
        foreach ($collect_data as $item) {
            foreach ($item as $key => $value) {
                $mapping[$key] = $value;
            }
        }

            function replaceKeyValue($value, $mapping) {
                list($key, $val) = explode('>', $value);
                $new_key = isset($mapping[$key]) ? $mapping[$key] : $key;
                $new_val = isset($mapping[$val]) ? $mapping[$val] : $val;
                return $new_key . '>' . $new_val;
            }

            foreach ($original_array as &$item) {
                $item['value'] = replaceKeyValue($item['value'], $mapping);
            }


            $selectanswer[0]['choices']=$original_array;
                                $field->setFormula(json_encode($selectanswer));
            $entityManager->flush();

                            }else  if($field->getFieldtype()===4){
                                $selectanswer = json_decode($field->getFormula(), true);
                                $newValueArray =$collect_data;

            foreach ($newValueArray as $item) {
            foreach ($item as $key => $value) {
            $selectanswer[0]['formula'] = preg_replace('/\b' . $key . '\b/', $value, $selectanswer[0]['formula']);
            }
            }

            $field->setFormula(json_encode($selectanswer));
            $entityManager->flush();

                }
        }

}