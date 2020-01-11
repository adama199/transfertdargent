<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déjà")
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide")
     * @Groups("get")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups("get")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups("get")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @Groups("get")
     *  @ApiSubresource(maxDepth=1) 
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get")
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActif;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function isAccountNonExpired(){
        return true;
    }
     public function isAccountNonLocked(){
         return true;
     }
     public function isCredentialsNonExpired()
     {
         return true;
     }
     public function isEnabled(){
         return $this->isActif;
     }

     public function getIsActif(): ?bool
     {
         return $this->isActif;
     }

     public function setIsActif(bool $isActif): self
     {
         $this->isActif = $isActif;

         return $this;
     }
}
