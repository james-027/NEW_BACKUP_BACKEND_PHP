<?php
namespace configuration_process;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'connection_form')]
class connection_form
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
    private $date_connected;

    public function setDateconnected( $data): void
    {
        $this->date_connected=$data;
    }
    public function getDateconnected()
    {
        return $this->date_connected;
    }

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private user|null $user_id = null;

    public function getUser()
    {
        return $this->user_id;
    }

    public function setUser( $data): void
    {
      $this->user_id = $data;
    }








}
