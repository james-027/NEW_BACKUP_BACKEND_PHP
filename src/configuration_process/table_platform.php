<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'table_platform')]
class table_platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }
    
    #[ORM\ManyToOne(targetEntity: platform::class, inversedBy:"platform")]
    #[ORM\JoinColumn(name: 'platform_id', referencedColumnName: 'id')]
    private platform|null $platform_id = null;
    public function getPlatform()
    {
        return $this->platform_id;
    }
    public function setPlatform( $data): void
    {
      $this->platform_id=$data;
    }
}
