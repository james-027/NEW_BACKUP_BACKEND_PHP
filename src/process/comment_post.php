<?php
namespace process;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'comment_post')]
class comment_post
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
    private string $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription( $data): void
    {      
        $this->description= $data;
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


    #[ORM\JoinTable(name: 'comment_post_react_comment_post')]
    #[ORM\JoinColumn(name: 'comment_post_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'react_comment_post_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: react_comment_post::class)]
    private Collection $react_comment_post;

    public function getReactcommentpost()
    {
        return $this->react_comment_post;
    }
    public function setReactcommentpost( $data): void
    {
        $this->react_comment_post->add($data);
    }   


    
    #[ORM\JoinTable(name: 'comment_post_comment_post')]
    #[ORM\JoinColumn(name: 'comment_post_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'comment_post_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: comment_post::class)]
    private Collection $link;

    public function getCommentpostlink()
    {
        return $this->link;
    }
    public function setCommentpostlink( $data): void
    {
        $this->link->add($data);
    }   


    public function __construct()
    {
    
        $this->react_comment_post = new ArrayCollection();
        $this->link = new ArrayCollection();

    }





}
