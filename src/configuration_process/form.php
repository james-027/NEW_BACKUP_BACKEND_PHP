<?php
namespace configuration_process;
use configuration\store;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'form')]
class form
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

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $series = null;

    public function getSeries()
    {   if($this->series!=null){
        return $this->series;
        }else{
        return 0;           
        }
    }

    public function setSeries ( $data):void
    {
        $this->series = $data;
    }
    

    #[ORM\Column(type: 'text',options:["default" => "0.0.0"], nullable:true)]
    private  $version;

    public function getVersion()
    {return $this->version;}

    public function setVersion( $data): void
    {$this->version = $data;}


    #[ORM\Column(type: 'text',options:["default" => "1.1.1"], nullable:true)]
    private  $chance;

    public function getChance()
    {return $this->chance;}

    public function setChance( $data): void
    {$this->chance = $data;}

    
    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $store_id = null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore( $data): void
    {      
        $this->store_id= $data;
    }


    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $type_id = null;

    public function getFormtype()
    {
        return $this->type_id;
    }

    public function setFormtype( $data): void
    {      
        $this->type_id= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private $remark;

    public function getRemark()
    {return $this->remark;}

    public function setRemark( $data): void
    {$this->remark = $data;}

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $open;

    public function setOpendate( $data): void
    {
        $this->open=$data;
    }
    public function getOpendate()
    {
        return $this->open;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $close;

    public function setClosedate( $data): void
    {
        $this->close=$data;
    }
    public function getClosedate()
    {
        return $this->close;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $parentform_id = null;

    public function getParentform()
    {
        return $this->parentform_id;
    }

    public function setParentform($data): void
    {      
        $this->parentform_id= $data;
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
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {      
        $this->created_by= $data;
    }


    #[ORM\JoinTable(name: 'form_task')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: task::class)]
    private Collection $form_task;

    public function getFormtask()
    {
        return $this->form_task;
    }
    public function setFormtask( $data): void
    {
        $this->form_task->add($data);
    }  

        
    public function removeFormtask($tasks,$data)
    {
        foreach ($tasks as $task) {
             if ($this->form_task->contains($data)) {
                 $this->form_task->removeElement($data);
             }
        }
       return $tasks;
    }  


    
    #[ORM\JoinTable(name: 'form_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $form_link;

    public function getFormlink()
    {
        return $this->form_link;
    }
    public function setFormlink($data): void
    {
        $this->form_link->add($data);
    }    





    #[ORM\JoinTable(name: 'form_connection_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'connection_form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: connection_form::class)]
    private Collection $connection_form;

    public function getConnectionform()
    {
        return $this->connection_form;
    }
    public function setConnectionform( $data): void
    {
        $this->connection_form->add($data);
    }   

    #[ORM\JoinTable(name: 'form_justification_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'justification_form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: justification_form::class)]
    private Collection $justification_form;

    public function getJustificationform()
    {
        return $this->justification_form;
    }
    public function setJustificationform( $data): void
    {
        $this->justification_form->add($data);
    }   


     
    #[ORM\JoinTable(name: 'form_attach_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'attach_form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $form_attach_form;

    public function getFormattach()
    {
        return $this->form_attach_form;
    }
    public function setFormattach( $data): void
    {
        $this->form_attach_form->add($data);
    }    
    

        
    #[ORM\JoinTable(name: 'form_reform')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'reform_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: reform::class)]
    private Collection $form_reform;

    public function getFormreform()
    {
        return $this->form_reform;
    }
    public function setFormreform( $data): void
    {
        $this->form_reform->add($data);
    }    


    #[ORM\ManyToMany(targetEntity: form::class, mappedBy: 'form_link')]
    private Collection $bidirectional;

        
    public function getBidirectional(): Collection
    {
        return $this->bidirectional;
    }



    public function __construct()
    {
        $this->form_task = new ArrayCollection();
        $this->form_attach_form = new ArrayCollection();
        $this->form_link = new ArrayCollection();
        $this->connection_form = new ArrayCollection();
        $this->form_reform = new ArrayCollection();
        $this->justification_form = new ArrayCollection();
        $this->bidirectional = new ArrayCollection();
    }




}
