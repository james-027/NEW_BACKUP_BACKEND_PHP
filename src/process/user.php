<?php
namespace process;
use configuration_process\form;
use configuration_process\itinerary;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'user')]
class user
{    
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId( $data): void
    {      
        $this->id= $data;
    }


    #[ORM\JoinTable(name: 'user_form_editor')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_editor;

    public function getUserformeditor()
    {
        return $this->user_form_editor;
    }
    public function setUserformeditor( $data): void
    {
        $this->user_form_editor->add($data);
    }

    
    #[ORM\JoinTable(name: 'user_form_generator')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_generator;

    public function getUserformgenerator()
    {
        return $this->user_form_generator;
    }
    public function setUserformgenerator( $data): void
    {
        $this->user_form_generator->add($data);
    }
        
    #[ORM\JoinTable(name: 'user_form_validation')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_validation;

    public function getUserformvalidation()
    {
        return $this->user_form_validation;
    }
    public function setUserformvalidation( $data): void
    {
        $this->user_form_validation->add($data);
    }

            
    #[ORM\JoinTable(name: 'user_form_connection')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_connection;

    public function getUserformconnection()
    {
        return $this->user_form_connection;
    }
    public function setUserformconnection( $data): void
    {
        $this->user_form_connection->add($data);
    }

    #[ORM\JoinTable(name: 'user_itinerary_generator')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: itinerary::class)]
    private Collection $user_itinerary_generator;

    public function getUseritinerarygenerator()
    {
        return $this->user_itinerary_generator;
    }
    public function setUseritinerarygenerator( $data): void
    {
        $this->user_itinerary_generator->add($data);
    }
    
    #[ORM\JoinTable(name: 'user_itinerary_connection')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: itinerary::class)]
    private Collection $user_itinerary_connection;

    public function getUseritineraryconnection()
    {
        return $this->user_itinerary_connection;
    }
    public function setUseritineraryconnection( $data): void
    {
        $this->user_itinerary_connection->add($data);
    }
    
    #[ORM\JoinTable(name: 'user_itinerary_validation')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: itinerary::class)]
    private Collection $user_itinerary_validation;

    public function getUseritineraryvalidation()
    {
        return $this->user_itinerary_validation;
    }
    public function setUseritineraryvalidation( $data): void
    {
        $this->user_itinerary_validation->add($data);
    }


    #[ORM\JoinTable(name: 'user_notification')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'notification_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: notification::class)]
    private Collection $user_notification;

    public function getUsernotification()
    {
        return $this->user_notification;
    }
    public function setUsernotification( $data): void
    {
        $this->user_notification->add($data);
    }

  



    public function __construct()
    {
        $this->user_form_editor = new ArrayCollection();
        $this->user_form_generator = new ArrayCollection();
        $this->user_form_validation = new ArrayCollection();
        $this->user_form_connection = new ArrayCollection();
        $this->user_itinerary_generator = new ArrayCollection();
        $this->user_itinerary_connection = new ArrayCollection();
        $this->user_itinerary_validation = new ArrayCollection();
        $this->user_notification = new ArrayCollection();
    }


}

