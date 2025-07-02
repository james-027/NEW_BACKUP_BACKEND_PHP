<?php
namespace configuration_process;
use process\store;
use process\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'preventive')]
class preventive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;
    
    public function getId()
    {
        return $this->id;
    }
    
    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $user_id = null;

    public function getUser()
    {
        return $this->user_id;
    }

    public function setUser( $data): void
    {
      $this->user_id = $data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $store_id = null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore( $data): void
    {      
        $this->store_id= $data;
    }

    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDateCreated( $data): void
    {
        $this->date_created=$data;
    }
    public function getDateCreated()
    {
        return $this->date_created;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_planned;

    public function setDateplanned( $data): void
    {
        $this->date_planned=$data;
    }
    public function getDateplanned()
    {
        return $this->date_planned;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_actual;

    public function setDateactual( $data): void
    {
        $this->date_actual=$data;
    }
    public function getDateactual()
    {
        return $this->date_actual;
    }


    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $itinerary_id = null;

    public function getItinerary()
    {
        return $this->itinerary_id;
    }
    public function setItinerary( $data): void
    {
        $this->itinerary_id=$data;
    }
    
    
    #[ORM\Column(type: 'text',nullable:true)]
    private string $remark;
    public function getRemark()
    {
        return $this->remark;
    }
    public function setRemark( $data): void
    {
        $this->remark = $data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $created_by = null;
    public function getCreatedBy()
    {
        return $this->created_by;
    }
    public function setCreatedBy( $data): void
    {
        $this->created_by=$data;
    }
    
    #[ORM\Column(type: 'boolean', nullable:true)]
    private $remove;

    public function getRemove()
    {
        return $this->remove;
    }

    public function setRemove($data): void
    {
        $this->remove=$data;
    }

}
