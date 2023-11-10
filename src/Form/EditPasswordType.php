<?php

namespace App\Form;

use App\Form\AppType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditPasswordType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('OldPassword',PasswordType::class,$this->conf("Ancien mot de passe :", "Tapez ici votre ancien mot de passe"))
        ->add('NewPassword',PasswordType::class,$this->conf("Nouveau mot de passe :", "Tapez ici votre nouveau mot de passe"))
        ->add('confirmPassword',PasswordType::class,$this->conf("Confirmation de mot de pass :", "Veuillez confirmer votre mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class,
        ]);
    }
}
