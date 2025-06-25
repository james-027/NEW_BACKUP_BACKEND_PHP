<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'automation_store')]
class automation_store
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
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($data): void
    {
        $this->file= $data;
    }

    #[ORM\ManyToOne(targetEntity: path::class, inversedBy:"path")]
    #[ORM\JoinColumn(name: 'path_id', referencedColumnName: 'id')]
    private path|null $path_id = null;

    public function getPath()
    {
        return $this->path_id;
    }
    public function setPath($data): void
    {
        $this->path_id = $data;
    }

    
    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private user|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby(user $data): void
    {
       $this->created_by=$data;
    }
    
    #[ORM\Column(type: 'boolean', nullable:true)]
    private $process;

    public function getProcess()
    {
        return $this->process;
    }

    public function setProcess($data): void
    {
        $this->process=$data;
    }

}
