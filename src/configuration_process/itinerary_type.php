<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'itinerary_type')]
class itinerary_type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $description;
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription( $data): void
    {      
        $this->description= $data;
    }
    
    #[ORM\JoinTable(name: 'itinerary_type_form')]
    #[ORM\JoinColumn(name: 'itinerary_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $itinerary_type_form;
    
    public function getItinerarytypeform()
    {
        return $this->itinerary_type_form;
    }
    
    public function setItinerarytypeform( $data): void
    {
        $this->itinerary_type_form->add($data);
    }
    
    public function removeItinerarytypeform($types,$data)
    {
        foreach ($types as $type) {
            if ($this->itinerary_type_form->contains($data)) {
                $this->itinerary_type_form->removeElement($data);
            }
        }
        return $types;
    }  
    
    public function __construct()
    {
        $this->itinerary_type_form = new ArrayCollection();
    }
}
