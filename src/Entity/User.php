<?php

namespace App\Entity;


use Serializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *      fields={"email"},
 *      message= "Cet email est déja utilisé"
 * )
 */
class User implements UserInterface, \Serializable

{
    const ROLE_ADMIN     = ['ROLE_ADMIN'];
    const ROLE_MANAGER   = ['ROLE_MANAGER'];
    const ROLE_USER      = ['ROLE_USER'];
    const DEFAULT_ROLE   = ['ROLE_USER'];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "Votre mot de passe doit contenir au moins {{ limit }} caractères"
     * )
     *
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *      message="veuillez saisir une adresse mail valide"
     * )
     */
    private $email;
    
    /**
     * @ORM\Column(type="simple_array", length=255, nullable=true)
     */
    private $roles;


    /**
     * @Assert\EqualTo(
     *      propertyPath="password",
     *      message = "Votre mot de passe doit être identique"
     * )
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDate;


    public function __construct()
    {
        $this->roles = self::DEFAULT_ROLE;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }



    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get the value of passwordConfirm
     */ 
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    /**
     * Set the value of passwordConfirm
     *
     * @return  self
     */ 
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function eraseCredentials(){}

    public function getSalt(){}

        
    /**
     * getRoles
     *
     * @return (Role|string)[] roles
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
    
    /**
     * Affecte les rôles de l'utilisateur
     *
     * @param  array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

}
