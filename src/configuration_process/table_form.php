<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'table_form')]
class table_form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }
    
    #[ORM\ManyToOne(targetEntity: form::class, inversedBy:"form")]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    private form|null $form_id = null;

    public function getForm()
    {
        return $this->form_id;
    }

    public function setForm( $data): void
    {
      $this->form_id=$data;
    }


    #[ORM\Column(type: 'boolean', nullable:true)]
    private $hide;

    public function getHide()
    {
        return $this->hide;
    }

    public function setHide($data): void
    {
        $this->hide=$data;
    }




}
