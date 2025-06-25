<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'chat')]
class chat
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
    private $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle( $data): void
    {      
        $this->title= $data;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated( $data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()
    {
        return $this->date_created;
    }


    
    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private user|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {
      $this->created_by=$data;
    }


     
    #[ORM\JoinTable(name: 'chat_user_monogamy')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $chat_user_monogamy;

    public function getUsermonogamy()
    {
        return $this->chat_user_monogamy;
    }
    public function setUsermonogamy( $data): void
    {
        $this->chat_user_monogamy->add($data);
    }   


    #[ORM\JoinTable(name: 'chat_user_polygamy')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $chat_user_polygamy;

    public function getUserpolygamy()
    {
        return $this->chat_user_polygamy;
    }
    public function setUserpolygamy( $data): void
    {
        $this->chat_user_polygamy->add($data);
    }   


    #[ORM\JoinTable(name: 'chat_message')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'message_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: message::class)]
    private Collection $chat_message;

    public function getChatmessage()
    {
        return $this->chat_message;
    }
    public function setChatmessage( $data): void
    {
        $this->chat_message->add($data);
    }   


    public function __construct()
    {
        $this->chat_user_monogamy = new ArrayCollection();
        $this->chat_user_polygamy = new ArrayCollection();
        $this->chat_message = new ArrayCollection();
   
    }




}
