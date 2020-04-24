<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\AppreciationGenerale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppreciationGeneraleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('CommentaireGeneral', TextareaType::class)
            ->add('eleve', EntityType::class, [
                 'class' => Eleve::class,
                 'choice_label' => 'slug'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppreciationGenerale::class,
        ]);
    }
}
