<?php
    class remove_category{
        public function setRemovecategory($entityManager,$origin_id){
            if(!$origin_id->getBidirectional()->isEmpty()){
                foreach($origin_id->getBidirectional() as $bidirectional){
                    $bidirectional->removeCategorylink($bidirectional->getCategorylink(),$origin_id);
                    $entityManager->flush();
                }
            }else{
                    $remove_origin= $entityManager->find(configuration\category::class,$origin_id);
                    $repository = $entityManager->getRepository(configuration\table_category::class);
                    $table_category = $repository->findOneBy(['category_id' => $remove_origin]);
                    $entityManager->remove($table_category); 
                    $entityManager->flush();  
                
            }
        }
    }

