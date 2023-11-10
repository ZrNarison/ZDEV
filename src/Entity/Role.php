<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Role
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
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userRoles")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $RoleSlug;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * Undocumented function
     *
     * @return integer|null
     */
    public function initializeSlug(){
        if(empty($this->RoleSlug)){
            $slugify= new Slugify();
            $this->RoleSlug = $slugify->Slugify($this->title);
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return string|null
     */
    public function __toString(): ?string
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getRoleSlug(): ?string
    {
        return $this->RoleSlug;
    }

    public function setRoleSlug(string $RoleSlug): self
    {
        $this->RoleSlug = $RoleSlug;

        return $this;
    }
}
