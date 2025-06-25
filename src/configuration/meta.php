<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'meta')]
class meta
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

    public function setTitle($data): void
    {
        $this->title = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $icon;

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($data): void
    {
        $this->icon = $data;
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

    #[ORM\Column(type: 'string',nullable:true)]
    private $theme_color;

    public function getThemecolor()
    {
        return $this->theme_color;
    }
    public function setThemecolor($data): void
    {
        $this->theme_color = $data;
    }
}
