<?php

namespace App\Entity;

use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping as ORM;

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
