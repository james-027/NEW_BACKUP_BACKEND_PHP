<?php
namespace configuration_process;
use configuration\store;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'automation_itinerary')]
class automation_itinerary
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
    private int|null $itinerary_type_id = null;

    public function getItinerarytype()
    {
        return $this->itinerary_type_id;
    }

    public function setItinerarytype( $data): void
    {      
        $this->itinerary_type_id= $data;
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


    #[ORM\Column(type: 'boolean', nullable:true)]
    private $process;

    public function getProcess()
    {
        return $this->process;
    }

    public function setProcess($data): void
    {
        $this->process=$data;
    }




    #[ORM\Column(type: 'text',nullable:true)]
    private string $justification;

    public function getJustification()
    {
        return $this->justification;
    }

    public function setJustification( $data): void
    {      
        $this->justification= $data;
    }


    #[ORM\Column(type:"date",nullable:true)]
    private $schedule;

    public function setSchedule( $data): void
    {
        $this->schedule=$data;
    }
    public function getSchedule()
    {
        return $this->schedule;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated( $data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()
    {
        return $this->date_created;
    }


   
    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {
      $this->created_by=$data;
    }



    
  

}
