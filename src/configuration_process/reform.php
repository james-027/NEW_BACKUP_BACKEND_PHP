<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'reform')]
class reform
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

    public function setDescription($data): void
    {
        $this->description = $data;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_update;

    public function setDateupdate( $data): void
    {
        $this->date_update=$data;
    }
    public function getDateupdate()
    {
        return $this->date_update;
    }


    #[ORM\Column(type: 'integer')]
    private int|null $updated_by = null;

    public function getUpdatedby()
    {
        return $this->updated_by;
    }

    public function setUpdatedby( $data): void
    {      
        $this->updated_by= $data;
    }


}


