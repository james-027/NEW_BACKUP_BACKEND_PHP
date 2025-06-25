<?php
namespace TemperatureDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'store')]
class store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $data): void
    {      
        $this->id= $data;
    }

    #[ORM\JoinTable(name: 'store_board')]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'board_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: board::class)]
    private Collection $store_board;

    public function getStoreBoard()
    {
        return $this->store_board; 
    }
    public function setStoreBoard( $data): void
    {
        $this->store_board->add($data);
    } 

    public function __construct()
    {
        $this->store_board = new ArrayCollection();  
    }
}

