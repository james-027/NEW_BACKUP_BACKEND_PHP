<?php
namespace configuration_process;
use configuration\path;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'platform')]
class platform
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




    #[ORM\JoinTable(name: 'platform_platform')]
    #[ORM\JoinColumn(name: 'platform_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'platform_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: platform::class)]
    private Collection $platform_link;

    public function getPlatformlink()
    {
        return $this->platform_link;
    }
    public function setPlatformlink( $data): void
    {
        $this->platform_link->add($data);
    }   


    public function removePlatformlink($links,$data)
    {
        foreach ($links as $link) {
             if ($this->platform_link->contains($data)) {
                 $this->platform_link->removeElement($data);
             }
        }
       return $links;
    }  


           
    #[ORM\ManyToMany(targetEntity: user_type::class, mappedBy: 'user_type_platform')]
    private Collection $user_type_platform;

    
    public function getUsertypeplatform(): Collection
    {
        return $this->user_type_platform;
    }



    public function __construct()
    {
        $this->platform_link = new ArrayCollection();
        $this->user_type_platform = new ArrayCollection();
      
    }



}
