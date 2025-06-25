<?php
namespace configuration_process;
use configuration_process\form;
use configuration_process\form_type;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'automation_form_publishing')]
class automation_form_publishing
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
    private int|null $form_id = null;
    public function getForm()
    {
        return $this->form_id;
    }

    public function setForm($data): void
    {
      $this->form_id = $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private $remark;

    public function getRemark()
    {
        return $this->remark;
    }

    public function setRemark($data): void
    {      
        $this->remark= $data;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_publish;

    public function setDatepublish($data): void
    {
        $this->date_publish=$data;
    }
    public function getDatepublish()    
    {
        return $this->date_publish;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated($data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()    
    {
        return $this->date_created;
    }

    

      #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby($data): void
    {
      $this->created_by=$data;
    }



      #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $form_type_id = null;

    public function getFormtype()
    {
        return $this->form_type_id;
    }

    public function setFormtype($data): void
    {      
        $this->form_type_id= $data;
    }


    #[ORM\Column(type: 'integer',nullable:true)]
    private int $priority;

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($data): void
    {      
        $this->priority= $data;
    }
}
