<?php
namespace process;
use configuration_process\form;
use configuration_process\itinerary;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'notification')]
class notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

   
    public function getId()
    {
        return $this->id;
    }
    

    #[ORM\ManyToOne(targetEntity: form::class, inversedBy:"react_type")]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    private form|null $form_id = null;

    
    public function getForm()
    {
        return $this->form_id;
    }

    public function setForm(  $data): void
    {
      $this->form_id=$data;
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
    
    #[ORM\ManyToOne(targetEntity: itinerary::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    private itinerary|null $itinerary_id = null;

    public function getItinerary()
    {
        return $this->itinerary_id;
    }

    public function setItinerary( $data): void
    {
      $this->itinerary_id=$data;
    }







}
