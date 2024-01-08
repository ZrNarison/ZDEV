<?php

namespace App\Entity;

use App\Repository\PosteurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosteurRepository::class)
 */
class Posteur
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
    private $Photo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Caption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="posteurs", cascade={"persist"})
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id")
     */
    private $ad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->Caption;
    }

    public function setCaption(string $Caption): self
    {
        $this->Caption = $Caption;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }
}
