<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use ORM\HasLifecycleCallbacks;
use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 */
class Ad
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
    private $Title;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Version;

    /**
     * @ORM\Column(type="date")
     */
    private $datedesortie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fichiers;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Techno;

    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Posteur", mappedBy="ad", cascade={"persist"})
     */
    private $posteurs;
    public function __construct()
    {
        $this->posteurs = new ArrayCollection();
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="auteur_id", referencedColumnName="id", nullable=false)
     */
    private $Auteur;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug(){
            $slugify= new Slugify();
            $this->Slug= $slugify->Slugify($this->Title) .'-'.$slugify->Slugify($this->Version);            
    }

    public function __toString()
    {
        return $this->getId() ? (string) $this->getId() : 'New Ad Entity';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->Version;
    }

    public function setVersion(string $Version): self
    {
        $this->Version = $Version;

        return $this;
    }

    public function getDatedesortie(): ?\DateTimeInterface
    {
        return $this->datedesortie;
    }

    public function setDatedesortie(\DateTimeInterface $datedesortie): self
    {
        $this->datedesortie = $datedesortie;

        return $this;
    }

    public function getFichiers(): ?string
    {
        return $this->fichiers;
    }

    public function setFichiers(string $fichiers): self
    {
        $this->fichiers = $fichiers;

        return $this;
    }


    public function getAuteur(): ?User
    {
        return $this->Auteur;
    }

    public function setAuteur(?User $Auteur): self
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    public function getTechno(): ?string
    {
        return $this->Techno;
    }

    public function setTechno(string $Techno): self
    {
        $this->Techno = $Techno;

        return $this;
    }

    /**
     * @return Collection<int, Posteur>
     */
    public function getPosteurs(): Collection
    {
        return $this->posteurs;
    }

    public function addPosteur(Posteur $posteur): self
    {
        if (!$this->posteurs->contains($posteur)) {
            $this->posteurs[] = $posteur;
            $posteur->setAd($this);
        }

        return $this;
    }

    public function removePosteur(Posteur $posteur): self
    {
        if ($this->posteurs->removeElement($posteur)) {
            // set the owning side to null (unless already changed)
            if ($posteur->getAd() === $this) {
                $posteur->setAd(null);
            }
        }

        return $this;
    }

    
    public function getSlug(): ?string
    {
        return $this->Slug;
    }

    public function setSlug(string $Slug): self
    {
        $this->Slug = $Slug;

        return $this;
    }
}
