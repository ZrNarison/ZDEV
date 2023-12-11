<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title',TextType::class, $this->conf("Nom : ","Nom de l'application"))
            ->add('Version',TextType::class, $this->conf("Version : ","En chiffre"))
            ->add('Techno',TextType::class, $this->conf("Techno : ","Langage de programmation utiliser"))
            ->add('fichiers',FileType::class, $this->conf("Image : ",""))
            ->add('Description',TextareaType::class, $this->conf("Description : ","Description"))
            ->add('datedesortie',DateType::class, $this->conf("Date de sortie : ","date ",["widget"=>"single_text"]))
            ->add(
                'posteurs',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true
                ]  
            )
            // ->add('button',Button::class,$this->conf("000 ","fdd",(['mapped'=>'false'])))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
