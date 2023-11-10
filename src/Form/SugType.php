<?php

namespace App\Form;

use App\Form\AppType;
use App\Entity\Suggestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SugType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Contact',TextType::class, $this->conf("Telephone : ","Numéro télephone"))
            ->add('Sugemail',EmailType::class, $this->conf("E-mail : ","Votre adress email"))
            ->add('SugComment',TextareaType::class, $this->conf("Suggestion : ","Merci de laisser votre .... Nous vous contactez plustard"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Suggestion::class,
        ]);
    }
}
