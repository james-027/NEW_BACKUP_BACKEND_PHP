<?php
namespace TemperatureDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;




#[ORM\Entity]
#[ORM\Table(name: 'oven')]
class oven
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string')]
    private string $description;

    public function getDescription()
    {
        return $this->description; 
    }
    public function setDescription( $data): void
    {      
        $this->description = $data;
    }

    #[ManyToMany(targetEntity: board::class, mappedBy: 'board')]
    private $board;

    #[ORM\ManyToOne(targetEntity: type::class, inversedBy:"type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private Type|null $type = null;

    public function setType( $data): void
    {
        $this->type = $data;
    }

    public function getType()
    {
        return $this->type;
    }


    #[ORM\JoinTable(name: 'oven_oven')]
    #[ORM\JoinColumn(name: 'oven_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'oven_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: oven::class)]
    private Collection $link;

    public function getLink()
    {
        return $this->link;
    }

    public function setLink( $data): void
    {
        $this->link->add($data);
    }

    #[ORM\JoinTable(name: 'oven_detail')]
    #[ORM\JoinColumn(name: 'oven_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'detail_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: detail::class)]
    private Collection $oven_detail;

    public function getOvenDetail()
    {
        return $this->oven_detail;
    }

    public function setOvenDetail( $data): void
    {
        $this->oven_detail->add($data);
    }

    public function __construct()
    {
        $this->oven_detail = new ArrayCollection(); 
	    $this->link = new ArrayCollection();    
    }
}	
