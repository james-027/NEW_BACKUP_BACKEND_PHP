<?php
namespace TemperatureDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'board')]
class board
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

    #[ManyToMany(targetEntity: store::class, mappedBy: 'store')]
    private $store;


    #[ORM\JoinTable(name: 'board_oven')]
    #[ORM\JoinColumn(name: 'board_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'oven_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: oven::class)]
    private Collection $board_oven;

    public function getBoardoven()
    {
        return $this->board_oven;
    }

    public function setBoardoven( $data): void
    {
        $this->board_oven->add($data);
    }


    #[ORM\JoinTable(name: 'board_store')]
    #[ORM\JoinColumn(name: 'board_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: store::class)]
    private Collection $board_store;



    public function getBoardstore()
    {
        return $this->board_store;
    }

    public function setBoardstore( $data): void
    {
        $this->board_store->add($data);
    }


    public function __construct()
    {
        $this->store = new ArrayCollection();
        $this->board_oven = new ArrayCollection();
        $this->board_store = new ArrayCollection();
    }




}


