<?php

namespace App\Entity;

use ORM\PreUpdate;
use ORM\PrePersist;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SuggestionRepository;

/**
 * @ORM\Entity(repositoryClass=SuggestionRepository::class)
 * @ORM\HasLifecycleCallBacks()
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
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * Undocumented function
     *
     * @return integer|null
     */
    public function initializedate(){
        if(empty($this->dateSuggestion)){
            $date= new Date();
            $this->dateSuggestion = $date;
        }
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * Undocumented function
     *
     * @return integer|null
     */
    public function initializeSlug(){
        if(empty($this->SugComment)){
            $slugify= new Slugify();
            $this->SugComment = $slugify->Slugify($this->Contact .'-'.  $this->Sugemail.'-'.  $this->dateSuggestion);
        }
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
}
