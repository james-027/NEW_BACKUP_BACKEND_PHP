<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'user_assign')]
class user_assign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }
    
    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $user_id = null;

    public function getUser()
    {
        return $this->user_id;
    }

    public function setUser( $data): void
    {      
        $this->user_id= $data;
    }




}
