<?php
namespace configuration_process;
use process\store;
use process\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'itinerary')]
class itinerary
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
    private int|null $type_id = null;

    public function getType()
    {
        return $this->type_id;
    }

    public function setType( $data): void
    {
      $this->type_id = $data;
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
    private $schedule;

    public function setSchedule( $data): void
    {
        $this->schedule = $data;
    }
    public function getSchedule()
    {
        return $this->schedule;
    }

    #[ORM\Column(type: 'boolean', nullable:true)]
    private $check_in;



    public function getCheckin()
    {
        return $this->check_in;
    }

    public function setCheckin( $data): void
    {      
        $this->check_in=$data;
    }
    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $check_in_time;

    public function setCheckintime( $data): void
    {
        $this->check_in_time = $data;
    }
    public function getCheckintime()
    {
        return $this->check_in_time;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $check_in_image;

    public function getCheckinimage()
    {
        return $this->check_in_image;
    }

    public function setCheckinimage( $data): void
    {      
        $this->check_in_image= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_in_latitude;

    public function getCheckinlatitude()
    {
        return $this->check_in_latitude;
    }

    public function setCheckinlatitude( $data): void
    {      
        $this->check_in_latitude= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_in_longitude;

    public function getCheckinlongitude()
    {
        return $this->check_in_longitude;
    }

    public function setCheckinlongitude( $data): void
    {      
        $this->check_in_longitude= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $check_in_remark;

    public function getCheckinremark()
    {return $this->check_in_remark;}

    public function setCheckinremark( $data): void
    {$this->check_in_remark = $data;}


    #[ORM\Column(type: 'boolean', nullable:true)]
    private $check_out;

    public function getCheckout()
    {
        return $this->check_out;
    }

    public function setCheckout( $data): void
    {      
        $this->check_out=$data;
    }


    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $check_out_time;

    public function setCheckouttime( $data): void
    {
        $this->check_out_time = $data;
    }
    public function getCheckouttime()
    {
        return $this->check_out_time;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $check_out_image;

    public function getCheckoutimage()
    {
        return $this->check_out_image;
    }

    public function setCheckoutimage( $data): void
    {      
        $this->check_out_image= $data;
    }


    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_out_latitude;

    public function getCheckoutlatitude()
    {
        return $this->check_out_latitude;
    }

    public function setCheckoutlatitude( $data): void
    {      
        $this->check_out_latitude= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_out_longitude;

    public function getCheckoutlongitude()
    {
        return $this->check_out_longitude;
    }

    public function setCheckoutlongitude( $data): void
    {      
        $this->check_out_longitude= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $check_out_remark;

    public function getCheckoutremark()
    {return $this->check_out_remark;}

    public function setCheckoutremark( $data): void
    {$this->check_out_remark = $data;}


    
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
    private $validation;

    public function getValidation()
    {
        return $this->validation;
    }

    public function setValidation( $data): void
    {      
        $this->validation=$data;
    }
    
    #[ORM\JoinTable(name: 'itinerary_connection_itinerary')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'connection_itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: connection_itinerary::class)]
    private Collection $connection_itinerary;

    public function getConnectionitinerary()
    {
        return $this->connection_itinerary;
    }
    public function setConnectionitinerary( $data): void
    {
        $this->connection_itinerary->add($data);
    }   

    #[ORM\JoinTable(name: 'itinerary_justification_itinerary')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'justification_itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: justification_itinerary::class)]
    private Collection $itinerary_justification;

    public function getItineraryjustification()
    {
        return $this->itinerary_justification;
    }
    public function setItineraryjustification( $data): void
    {
        $this->itinerary_justification->add($data);
    }   
    #[ORM\JoinTable(name: 'itinerary_reform')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'reform_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: reform::class)]
    private Collection $itinerary_reform;

    public function getItineraryreform()
    {
        return $this->itinerary_reform;
    }
    public function setItineraryreform( $data): void
    {
        $this->itinerary_reform->add($data);
    }    

    #[ORM\JoinTable(name: 'itinerary_form')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $itinerary_form;

    public function getItineraryform()
    {
        return $this->itinerary_form;
    }
    public function setItineraryform( $data): void
    {
        $this->itinerary_form->add($data);
    }
    
    #[ORM\JoinTable(name: 'itinerary_tracker_itinerary')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'tracker_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: track_itinerary::class)]
    private Collection $itinerary_tracker_itinerary;

    public function getItinerarytracker()
    {
        return $this->itinerary_tracker_itinerary;
    }
    public function setItinerarytracker( $data): void
    {
        $this->itinerary_tracker_itinerary->add($data);
    }    

    public function __construct()
    {
    
        $this->connection_itinerary = new ArrayCollection();
        $this->itinerary_reform = new ArrayCollection();
        $this->itinerary_justification = new ArrayCollection();
        $this->itinerary_form = new ArrayCollection();
        $this->itinerary_tracker = new ArrayCollection();
    }
}
