<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'field_type')]
class field_type
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

    #[ORM\Column(type: 'string',nullable:true)]
    private $icon;

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon( $data): void
    {      
        $this->icon= $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private $label;

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel( $data): void
    {      
        $this->label= $data;
    }


    #[ORM\Column(type: 'integer')]
    private int|null $path_id = null;


    public function getPath()
    {
        return $this->path_id;
    }

    public function setPath( $data): void
    {      
        $this->path_id= $data;
    }



}
