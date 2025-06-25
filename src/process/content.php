<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'content')]
class content
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



         
    #[ORM\JoinTable(name: 'content_react_content')]
    #[ORM\JoinColumn(name: 'content_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'react_content_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: react_content::class)]
    private Collection $react_content;

    public function getReactcontent(): Collection
    {
        return $this->react_content;
    }
    public function setReactcontent( $data): void
    {
        $this->react_content->add($data);
    }   


    #[ORM\JoinTable(name: 'content_comment_content')]
    #[ORM\JoinColumn(name: 'content_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'comment_content_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: comment_content::class)]
    private Collection $comment_content;

    public function getContentcomment()
    {
        return $this->comment_content;
    }
    public function setContentcomment( $data): void
    {
        $this->comment_content->add($data);
    }   


    public function __construct()
    {
        $this->react_content = new ArrayCollection();
        $this->comment_content = new ArrayCollection();
    
    }






}
