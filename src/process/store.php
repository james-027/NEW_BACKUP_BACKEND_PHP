<?php
namespace process;
use configuration_process\form;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'store')]
class store
{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;


    public function getId()
    {
        return $this->id;
    }

    public function setId( $data): void
    {      
        $this->id= $data;
    }

    #[ORM\JoinTable(name: 'store_form')]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $store_form;

    public function getStorename()
    {
        return $this->store_form;
    }
    public function setStorename( $data): void
    {
        $this->store_form->add($data);
    }

    
    public function __construct()
    {
        $this->store_form = new ArrayCollection();

    }


}

