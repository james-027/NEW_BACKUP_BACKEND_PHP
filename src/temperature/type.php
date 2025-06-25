<?php
namespace TemperatureDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'type')]
class type
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

    public function setDescription( $data): void
    {      
        $this->description = $data;
    }

	public function description()
    {

    return $this->description;
    }


 

  
}

