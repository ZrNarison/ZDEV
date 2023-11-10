<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
class UpdatePassword
{
    

    private $oldPassword;

    /**
     * @Assert\Length(min=6,minMessage="Mot de pas trop court, il doit faire au moins six(6)caractéres !",max=20,maxMessage="Mot de pass ne doivent pas dépassé des vingt(20) caractéres !")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword",message="Code de confirmation incorrect !")
     */
    private $confirmPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
