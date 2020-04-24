<?php

namespace App\Form;

use App\Entity\Prof;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libelle',
                'required' => true,
            ])
            ->add('annee', ChoiceType::class, [
                'label' => 'Année',
                'data' => $options['annee'],
                'choices' => [
                    'Non spécifié' => null,
                    'Première année' => 1,
                    'Second année' => 2,
                    'Troisième année' => 3
                ]
            ])
        ;

        dump($options['annee']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
            'annee' => null,
        ]);
    }
}
