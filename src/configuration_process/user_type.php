<?php
namespace configuration_process;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'user_type')]
class user_type
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

    #[ORM\OneToMany(mappedBy: 'type_id', targetEntity: user::class)]
    private Collection $users; 
    
    #[ORM\JoinTable(name: 'user_type_platform')]
    #[ORM\JoinColumn(name: 'user_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'platform_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: platform::class)]
    private Collection $user_type_platform;

    public function getUsertypeplatform()
    {
        return $this->user_type_platform;
    }
    public function setUsertypeplatform( $data): void
    {
        $this->user_type_platform->add($data);
    }
    
    public function removeUsertypeplatform($links,$data)
    {
        foreach ($links as $link) {
            if ($this->user_type_platform->contains($data)) {
                $this->user_type_platform->removeElement($data);
            }
        }
      return $links;
    }  

    #[ORM\JoinTable(name: 'user_type_form')]
    #[ORM\JoinColumn(name: 'user_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_type_form;

    public function getUsertypeform()
    {
        return $this->user_type_form;
    }
    public function setUsertypeform( $data): void
    {
        $this->user_type_form->add($data);
    }


        public function removeUsertypeform($forms,$data)
    {
        foreach ($forms as $form) {
            if ($this->user_type_form->contains($data)) {
                    $this->user_type_form->removeElement($data);
            }
        }
       return $forms;
    }  

        #[ORM\JoinTable(name: 'user_type_itinerary_type')]
    #[ORM\JoinColumn(name: 'user_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'itinerary_type_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: itinerary_type::class)]
    private Collection $user_type_itinerary_type;

    public function getUsertypeitinerarytype()
    {
        return $this->user_type_itinerary_type;
    }
    public function setUsertypeitinerarytype( $data): void
    {
        $this->user_type_itinerary_type->add($data);
    }

    public function removeUsertypeitinerarytype($itinerarys,$data)
    {
        foreach ($itinerarys as $itinerary) {
            if ($this->user_type_itinerary_type->contains($data)) {
                    $this->user_type_itinerary_type->removeElement($data);
            }
        }
        return $itinerarys;
    }  

    public function __construct()
    {
        $this->user_type_platform = new ArrayCollection();
        $this->user_type_form = new ArrayCollection();
        $this->user_type_itinerary_type = new ArrayCollection();
    }
}
