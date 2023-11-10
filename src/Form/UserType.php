<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Form\AppType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, $this->conf("Nom : ","Nom d'utilisateur"))
            ->add('lastname',TextType::class, $this->conf("Prénom : ","Prénom d'utilisateur"))
            ->add('email',TextType::class, $this->conf("E-mail : ","Adress emiail"))
            ->add('ph',FileType::class, $this->conf("Photo : ",""))
            ->add('psd',PasswordType::class, $this->conf("Mot de passe : ","Mot de passe"))
            ->add('Confirmationpsd',PasswordType::class, $this->conf("Confirmation : ","Veuillez confirmer votre mot de pass"))
            ->add('information',TextareaType::class, $this->conf("Information : ","Information"))
            ->add('Categorie',EntityType::class,[
                'mapped'=> false,
                'class'=> Role::class,
                'choice_label'=>'title'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
