<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ph;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $psd;

    public $Confirmationpsd;

    /**
     * @ORM\Column(type="text")
     */
    private $information;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SlugUser;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="Auteur", orphanRemoval=true)
     */
    private $ads;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * Undocumented function
     *
     * @return void
     */
    public function initializeSlug(){
        if(empty($this->ProjectSlug)){
            $slugify= new Slugify();
            $this->ProjectSlug = $slugify->Slugify($this->firstname .''.  $this->email);
        }
    }

    public function __construct()
    {
        $this->ads = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getPh(): ?string
    {
        return $this->ph;
    }

    public function setPh(?string $ph): self
    {
        $this->ph = $ph;

        return $this;
    }

    public function getPsd(): ?string
    {
        return $this->psd;
    }

    public function setPsd(string $psd): self
    {
        $this->psd = $psd;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getSlugUser(): ?string
    {
        return $this->SlugUser;
    }

    public function setSlugUser(string $SlugUser): self
    {
        $this->SlugUser = $SlugUser;

        return $this;
    }

    /**
     * @return Collection<int, Ad>
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAuteur($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAuteur() === $this) {
                $ad->setAuteur(null);
            }
        }

        return $this;
    }
    public function getRoles(){
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();
        return $roles;
    }

    public function getPassword(){
        return $this->psd; 
    }

    public function getSalt(){}

    public function eraseCredentials(){}

    public function getUsername(){
        return $this->email; 
    }

    /**
     * @return Collection<int, Role>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }
}
