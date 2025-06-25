<?php
namespace configuration;
use configuration_process\user_type;
use configuration_process\form;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ReallySimpleJWT\Token;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Validate;
use ReallySimpleJwt\Decode;
use ReallySimpleJwt\Helper\Validator;
use ReallySimpleJWT\Exception\ValidateException;
use ReallySimpleJWT\Exception\TokenException;


class tokens {
    private int $id;
    private const SECRET_KEY = 'secyew44wfdfd23wsdsdsdzsad!ReT423*&';
    public function getToken(int $id)
    {
        return Token::create($id, self::SECRET_KEY, time() + 2000000, "*", ['alg' => 'HS256']);
    }

    public function getValidation(string $myToken)
    {
        try {
            return Token::validate($myToken, self::SECRET_KEY);
        } catch (ParsedException $e) {
            return false;
        }
    }

    public function decodeToken(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        $payload = $this->base64UrlDecode($parts[1]);
        $decodedPayload = json_decode($payload, true);
        if ($decodedPayload && isset($decodedPayload['user_id'])) {
            return $decodedPayload;
        }

        return null;
    }


    private function base64UrlDecode(string $data)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= str_repeat('=', 4 - $mod4);
        }

        return base64_decode($data);
    }
}



#[ORM\Entity]
#[ORM\Table(name: 'user')]
class user
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;
    
    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: user::class)]
    private Collection $users; 

    public function getId(): int
    {
        return $this->id;
    }
    
    #[ORM\Column(type: 'string',nullable:true)]
    private string $username;

    public function getUsername()
    {   
        return $this->username; 
    }

    public function setUsername($data): void
    {      
        $this->username = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $password;

    public function setPassword($data): void
    {
      $this->password = password_hash($data,PASSWORD_DEFAULT);
    }    
    public function authenticate_user($data)
    {
        if (password_verify($data, $this->password)) {
            return true;
        }
        return false;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $database_name;

    public function getDatabasename()
    {   
        return $this->database_name;
    }

    public function setDatabasename($data): void
    {      
        $this->database_name = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $first_name;

    public function getFirstname()
    {   
        return $this->first_name;
    }

    public function setFirstname($data): void
    {      
        $this->first_name = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $middle_name;

    public function getMiddlename()
    {   
        return $this->middle_name; 
    }

    public function setMiddlename($data): void
    {      
        $this->middle_name = $data;
    }
    
    #[ORM\Column(type: 'string',nullable:true)]
    private $last_name;
    public function getLastname()
    {   
        return $this->last_name;
    }

    public function setLastname($data): void
    {      
        $this->last_name = $data;
    }
    

    #[ORM\Column(type: 'string',nullable:true)]
    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($data): void
    {      
        $this->email= $data;
    }

    #[ORM\Column(type: 'string',nullable:true, options:["default" => "0"])]
    private $employee_number;

    public function getEmployeenumber()
    {   
        return $this->employee_number; 
    }

    public function setEmployeenumber($data): void
    {      
        $this->employee_number = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $location;


    public function getLocation()
    {   
        return $this->location; 
    }

    public function setLocation($data):void
    {
        $this->location = $data;
    }

    #[ORM\Column(type:"datetime",nullable:true)]
    private $time_location;

    public function getTimelocation()
    {
        return $this->time_location;
    }

    public function setTimelocation($data):void
    {
        $this->time_location = $data;
    }

    #[ORM\ManyToOne(targetEntity: store::class, inversedBy:"users")]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    private store|null $store_id=  null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore($data): void
    {
      $this->store_id=$data;
    }

    #[ORM\ManyToOne(targetEntity: user_type::class, inversedBy:"users")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private user_type|null $type_id = null;

    public function getUsertype()
    {
        return $this->type_id;
    }

    public function setUsertype($data): void
    {
      $this->type_id=$data;
    }


    #[ORM\Column(type: 'boolean', nullable:true)]
    private $activate;

    public function getActivate()
    {
        return $this->activate;
    }

    public function setActivate($data): void
    {
        $this->activate=$data;
    }

    #[ORM\Column(type: 'boolean', nullable:true)]
    private $change_password;

    public function getChangepassword()
    {
        return $this->change_password;
    }

    public function setChangepassword($data): void
    {
        $this->change_password=$data;
    }

    #[ORM\Column(type: 'decimal', precision: 15, scale: 4, nullable: true)]
    private  $distance;

    public function getDistance()
    {
        return $this->distance;
    }

    public function setDistance($data): void
    {
        $this->distance=$data;
    }

    #[ORM\Column(type: 'string',options:["default" => "profile.png"],nullable:true)]
    private  $picture;

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): void
    {
        $this->picture = $picture;
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
    
    
    #[ORM\JoinTable(name: 'user_store')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: store::class)]
    private Collection $user_store;

    public function getUserstore()
    {
        return $this->user_store;
    }
    public function setUserstore($data): void
    {
        $this->user_store->add($data);
    }

    #[ORM\JoinTable(name: 'user_assign_user')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $user_assign_user;

    public function getUserassign()
    {
        return $this->user_assign_user;
    }
    public function setUserassign($data): void
    {
        $this->user_assign_user->add($data);
    }


        public function removeUserassignuser($users,$data)
    {
        foreach ($users as $user) {
             if ($this->user_assign_user->contains($data)) {
                 $this->user_assign_user->removeElement($data);
             }
        }
       return $users;
    }  


    #[ORM\JoinTable(name: 'user_user')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $user_user;

    public function getUserlink()
    {
        return $this->user_user;
    }
    public function setUserlink($data): void
    {
        $this->user_user->add($data);
    }

    public function removeUserlink($users,$data)
    {
        foreach ($users as $user) {
             if ($this->user_user->contains($data)) {
                 $this->user_user->removeElement($data);
             }
        }
       return $users;
    }  

    
    #[ORM\JoinTable(name: 'user_category')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: category::class)]
    private Collection $user_category;

    public function getUsercategory()
    {
        return $this->user_category;
    }
    public function setUsercategory($data): void
    {
        $this->user_category->add($data);
    }

    public function removeUsercategory($users,$data)
    {
        foreach ($users as $user) {
             if ($this->user_category->contains($data)) {
                 $this->user_category->removeElement($data);
             }
        }
       return $users;
    }  



     #[ORM\ManyToMany(targetEntity: user::class, mappedBy: 'user_user')]
    private Collection $bidirectional;

        
    public function getBidirectional(): Collection
    {
        return $this->bidirectional;
    }
    
    public function __construct()
    {
        $this->user_store = new ArrayCollection();
        $this->user_user = new ArrayCollection();
        $this->bidirectional = new ArrayCollection();
        $this->user_assign_user = new ArrayCollection();
        $this->user_category = new ArrayCollection();
    }


}
