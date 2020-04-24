<?php

namespace App\Form;

use App\Entity\Prof;
use App\Entity\Classe;
use App\Entity\Matiere;
use App\Entity\Enseignement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('classe', EntityType::class, [
                'label' => 'Classe',
                'class' => Classe::class,
                'choice_label' => 'slug',
                'required' => true,
            ])
            ->add('matiere', EntityType::class, [
                'label' => 'Matière',
                'class' => Matiere::class,
                'choice_label' => 'slug',
                'required' => true,
            ])
            ->add('prof', EntityType::class, [
                'label' => 'Professeur',
                'class' => Prof::class,
                'choice_label' => 'slug',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseignement::class,
        ]);
    }
}
