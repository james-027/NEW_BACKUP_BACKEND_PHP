<?php
namespace TemperatureDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'detail')]
class detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {

    return $this->id;
    }   


    #[ORM\Column(type: 'text',nullable:true)]
    private string $value;

    public function setValue( $data): void
    {      
        $this->value = $data;
    }
	
    
    public function getValue()
    {
        return $this->value;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $datetime;

    public function setDatetime( $datetime): void
    {      
        $this->datetime=$datetime;
    }
    public function getDateTime()
    {

    return $this->datetime;
    }


    #[ORM\ManyToOne(targetEntity: type::class, inversedBy:"detail")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private type|null $type = null;

    public function setType( $data): void
    {      
        $this->type = $data;
    }

    public function getType()
    {
    return $this->type;
    }

   

}
