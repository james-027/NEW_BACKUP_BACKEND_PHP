<?php
    class remove_platform{
        public function setRemoveplatform($entityManager,$origin_id,$platform_id,$user_type_id){
            $origin = $entityManager->find(configuration_process\platform::class,$origin_id);
            if($origin){
                foreach($origin->getUsertypeplatform() as $user_type){
                    if($user_type->getId() === $user_type_id){
                        $user_type_id = $entityManager->find(configuration_process\user_type::class,$user_type_id);
                        $user_type_id->removeUsertypeplatform($user_type_id->getUsertypeplatform(),$origin);
                        $entityManager->flush();
                    }
                }
            }
            $platform = $entityManager->find(configuration_process\platform::class,$platform_id);
            if($platform){
                foreach($platform->getPlatformlink() as $link){
                        $link_platform = $entityManager->find(configuration_process\platform::class,$link->getId());
                    if($platform){
                        $this->setRemoveplatform($entityManager,$origin_id,$link->getId(),$user_type_id); 
                    }
                        $entityManager->remove($link_platform);
                        $entityManager->flush();
                }
            }  
            try{
                $remove_origin= $entityManager->find(configuration_process\platform::class,$origin_id);
                if ($remove_origin){
                    $repository = $entityManager->getRepository(configuration_process\table_platform::class);
                    $table_platform = $repository->findOneBy(['platform_id' => $remove_origin]);
                    if($table_platform){
                        $entityManager->remove($table_platform); 
                        $entityManager->flush();  
                        $entityManager->remove($remove_origin);
                        $entityManager->flush();  
                    }else{
                        $entityManager->remove($remove_origin);
                        $entityManager->flush();  
                    }
                
                }
            }catch(Exception $e){
                echo json_encode($e->getMessage());
            }
        }
    }

