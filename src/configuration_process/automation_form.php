<?php
namespace configuration_process;
use configuration_process\form;
use configuration_process\itinerary;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'automation_form')]
class automation_form
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
    private int|null $form_id = null;

    public function getForm()
    {
        return $this->form_id;
    }

    public function setForm( $data): void
    {      
        $this->form_id= $data;
    }


    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {      
        $this->created_by= $data;
    }



    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $itinerary_id = null;

    public function getItinerary()
    {
        return $this->itinerary_id;
    }

    public function setItinerary( $data): void
    {      
        $this->itinerary_id= $data;
    }

}
