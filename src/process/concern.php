<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'concern')]
class concern
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




     
    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'solved_by', referencedColumnName: 'id')]
    private user|null $solved_by = null;

    public function getSolvedby()
    {
        return $this->solved_by;
    }

    public function setSolvedby( $data): void
    {
      $this->solved_by=$data;
    }


    
    #[ORM\Column(type: 'boolean', nullable:true)]
    private $solve;

    public function getSolve()
    {
        return $this->solve;
    }

    public function setSolve( $data): void
    {      
        $this->solve=$data;
    }



    #[ORM\JoinTable(name: 'concern_justification_concern')]
    #[ORM\JoinColumn(name: 'concern_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'justification_concern_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: justification_concern::class)]
    private Collection $justification_concern;

    public function getJustificationconcern()
    {
        return $this->justification_concern;
    }
    public function setJustificationconcern( $data): void
    {
        $this->justification_concern->add($data);
    }   


    public function __construct()
    {
        $this->react_post = new ArrayCollection();
        $this->justification_concern = new ArrayCollection();

    }





}
