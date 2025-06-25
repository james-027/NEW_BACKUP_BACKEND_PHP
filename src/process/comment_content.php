<?php
namespace process;
use configuration_process\react_type;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'comment_content')]
class comment_content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

   
    public function getId()
    {
        return $this->id;
    }
        
    #[ORM\ManyToOne(targetEntity: react_type::class, inversedBy:"react_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private react_type|null $type_id = null;

    
    public function getType()
    {
        return $this->type_id;
    }

    public function setType(  $data): void
    {
      $this->type_id=$data;
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


    #[ORM\JoinTable(name: 'comment_content_react_comment_content')]
    #[ORM\JoinColumn(name: 'comment_content_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'react_comment_content_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: react_comment_content::class)]
    private Collection $react_comment_content;

    public function getReactcommentcontent()
    {
        return $this->react_comment_content;
    }
    public function setReactcommentcontent( $data): void
    {
        $this->react_comment_content->add($data);
    }   


    #[ORM\JoinTable(name: 'comment_content_comment_content')]
    #[ORM\JoinColumn(name: 'comment_content_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'comment_content_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: comment_content::class)]
    private Collection $comment_content;

    public function getCommentcontentlink()
    {
        return $this->comment_content;
    }
    public function setCommentcontentlink( $data): void
    {
        $this->comment_content->add($data);
    }   

    public function __construct()
    {
    
        $this->react_comment_content = new ArrayCollection();
        $this->comment_content = new ArrayCollection();

    }





}
