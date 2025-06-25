<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'task')]
class task
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
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription( $data): void
    {      
        $this->description= $data;
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

    
    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $status_id = null;
    public function getStatus()
    {
        return $this->status_id;
    }


    public function setStatus( $data): void
    {      
        $this->status_id= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private  $style;

    public function getStyle()
    {
        return $this->style;
    }

    public function setStyle( $data): void
    {      
        $this->style= $data;
    }

    #[ORM\Column(type: 'bigint',nullable:true)]
    private int|null $row_set = null;
    
    public function getRowset()
    {
        return $this->row_set;
    }
    public function setRowset( $data): void
    {      
        $this->row_set= $data;
    }

    #[ORM\Column(type: 'bigint',nullable:true)]
    private int|null $col_set = null;
    
    public function getColset()
    {
        return $this->col_set;
    }
    public function setColset( $data): void
    {      
        $this->col_set= $data;
    }

    #[ORM\JoinTable(name: 'task_validation')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'validation_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: validation::class)]
    private Collection $task_validation;
    public function getTaskvalidation()
    {
        return $this->task_validation;
    }
    public function setTaskvalidation($data): void
    {
    $this->task_validation->add($data);
    }
    public function removeTaskvalidation($tasks,$data)
    {
        foreach ($tasks as $task) {
            if ($this->task_validation->contains($data)) {
                    $this->task_validation->removeElement($data);
            }
        }
       return $tasks;
    }  

    #[ORM\JoinTable(name: 'task_assign')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'assign_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: assign::class)]
    private Collection $task_assign;
    public function getTaskassign()
    {
        return $this->task_assign;
    }
    public function setTaskassign($data): void
    {
    $this->task_assign->add($data);
    }
    public function removeTaskassign($tasks,$data)
    {
        foreach ($tasks as $task) {
            if ($this->task_assign->contains($data)) {
                    $this->task_assign->removeElement($data);
            }
        }
       return $tasks;
    }  
    
    
    #[ORM\JoinTable(name: 'task_field')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'field_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: field::class)]
    private Collection $task_field;

    public function getTaskfield()
    {
        return $this->task_field;
    }
    public function setTaskfield( $data): void
    {
        $this->task_field->add($data);
    }

    #[ORM\JoinTable(name: 'task_task')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'task_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: task::class)]
    private Collection $task_link;

    public function getTasklink()
    {
        return $this->task_link;
    }
    public function setTasklink( $data): void
    {
        $this->task_link->add($data);
    }
    
    public function removeTaskfield($tasks,$data)
    {
        foreach ($tasks as $task) {
             if ($this->task_field->contains($data)) {
                 $this->task_field->removeElement($data);
             }
        }
       return $tasks;
    }  

    public function setAlltaskfield(Collection $data): void
    {
        $this->task_field = $data;
    } 


    public function __construct()
    {
        $this->task_field = new ArrayCollection();
        $this->task_link = new ArrayCollection();
        $this->task_validation = new ArrayCollection();
        $this->task_assign = new ArrayCollection();
    }



}
