<?php

namespace App\Entity;

use ORM\PreUpdate;
use ORM\PrePersist;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=SuggestionRepository::class)
 * @HasLifecycleCallbacks()
 */
class Suggestion
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
    private $Contact;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Sugemail;

    /**
     * @ORM\Column(type="text")
     */
    private $SugComment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSuggestion;

    /**
     * @ORM\Column(type="text")
     */
    private $SugSlug;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * Undocumented function
     *
     * @return integer|null
     */
    public function initializedate(){
        $this->dateSuggestion = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return string|null
     */
    public function __toString()
    {
        return $this->getDateSuggestion();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * Undocumented function
     *
     * @return integer|null
     */
    public function initializeSlug(){
        $slugify= new Slugify();
        $this->SugSlug = $slugify->Slugify($this->Contact .'-'.  $this->Sugemail);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContact(): ?string
    {
        return $this->Contact;
    }

    public function setContact(string $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
    }

    public function getSugemail(): ?string
    {
        return $this->Sugemail;
    }

    public function setSugemail(string $Sugemail): self
    {
        $this->Sugemail = $Sugemail;

        return $this;
    }

    public function getSugComment(): ?string
    {
        return $this->SugComment;
    }

    public function setSugComment(string $SugComment): self
    {
        $this->SugComment = $SugComment;

        return $this;
    }

    public function getDateSuggestion(): ?\DateTimeInterface
    {
        return $this->dateSuggestion;
    }

    public function setDateSuggestion(\DateTimeInterface $dateSuggestion): self
    {
        $this->dateSuggestion = $dateSuggestion;

        return $this;
    }

    public function getSugSlug(): ?string
    {
        return $this->SugSlug;
    }

    public function setSugSlug(string $SugSlug): self
    {
        $this->SugSlug = $SugSlug;

        return $this;
    }
}
