<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;


    public function getId(): int
    {
        return $this->id;
    }
    
    
    #[ORM\Column(type: 'string',nullable:true)]
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($data): void
    {      
        $this->description= $data;
    }

    #[ORM\JoinTable(name: 'category_user')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $category_user;

    public function getCategoryuser()
    {
        return $this->category_user;
    }
    public function setCategoryuser($data): void
    {
        $this->category_user->add($data);
    }

    
    public function removeCategoryuser($users,$data)
    {
        foreach ($users as $user) {
             if ($this->category_user->contains($data)) {
                 $this->category_user->removeElement($data);
             }
        }
       return $users;
    }  
 
    // #[ORM\JoinTable(name: 'category_store')]
    // #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    // #[ORM\InverseJoinColumn(name: 'store_id', referencedColumnName: 'id')]
    // #[ORM\ManyToMany(targetEntity: store::class)]
    // private Collection $category_store;

    // public function getCategorystore()
    // {
    //     return $this->category_store;
    // }
    // public function setCategorystore($data): void
    // {
    //     $this->category_store->add($data);
    // }
    
    // #[ORM\JoinTable(name: 'category_category')]
    // #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    // #[ORM\InverseJoinColumn(name: 'category_related_id', referencedColumnName: 'id')]
    // #[ORM\ManyToMany(targetEntity: category::class)]
    // private Collection $category_link;

    // public function getCategorylink()
    // {
    //     return $this->category_link;
    // }
    // public function setCategorylink($data): void
    // {
    //     $this->category_link->add($data);
    // }

    // public function removeCategorylink($categories,$data)
    // {
    //     foreach ($categories as $category) {
    //         if ($this->category_link->contains($data)) {
    //             $this->category_link->removeElement($data);
    //         }
    //     }
    //    return $categories;
    // }  

    // public function setAllcategorylink(Collection $data): void
    // {
    //     $this->category_link = $data;
    // } 

    // #[ORM\ManyToMany(targetEntity: category::class, mappedBy: 'category_link')]
    // private Collection $bidirectional;

        
    // public function getBidirectional(): Collection
    // {
    //     return $this->bidirectional;
    // }

    public function __construct()
    {
        $this->category_user = new ArrayCollection();
      //  $this->category_store = new ArrayCollection();
     //   $this->category_link = new ArrayCollection();
       // $this->bidirectional = new ArrayCollection();
    }



}
