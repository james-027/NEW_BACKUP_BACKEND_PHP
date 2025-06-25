<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use configuration\user;



#[ORM\Entity]
#[ORM\Table(name: 'assign')]
class assign
{
 


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;
 
    public function getId()
    {
        return $this->id;
    }
    
    #[ORM\Column(type: 'boolean', nullable:true)]
    private $valid;

    public function getValid()
    {
        return $this->valid;
    }

    public function setValid( $data): void
    {      
        $this->valid=$data;
    }

  
    #[ORM\Column(type: 'integer', nullable:true)]
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {
      $this->created_by=$data;
    }

 
    #[ORM\Column(type: 'integer', nullable:true)]
    private int|null $user_type_id = null;
    
    public function getUsertype()
    {
        return $this->user_type_id;
    }
    
    public function setUsertype( $data): void
    {
      $this->user_type_id=$data;
    }





}
