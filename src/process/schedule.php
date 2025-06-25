<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'schedule')]
class schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }
    

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_effective;

    public function setDateeffective( $data): void
    {
        $this->date_effective=$data;
    }
    public function getDateeffective()
    {
        return $this->date_effective;
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



    #[ORM\JoinTable(name: 'schedule_user_assign')]
    #[ORM\JoinColumn(name: 'schedule_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_assign_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user_assign::class)]
    private Collection $schedule_user_assign;

    public function getScheduleuserassign()
    {
        return $this->schedule_user_assign;
    }
    public function setScheduleuserassign( $data): void
    {
        $this->schedule_user_assign->add($data);
    }   



    public function __construct()
    {
        
        $this->schedule_user_assign = new ArrayCollection();
    }


  


}
