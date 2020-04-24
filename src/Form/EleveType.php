<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Tel', TelType::class, [
                'label' => "Téléphone mobile",
                'required' => false,
            ])
            ->add('CP', TextType::class, [
                'label' => "Code postal",
                'required' => false,
            ])
            ->add('Ville', TextType::class, [
                'label' => "Ville",
                'required' => false,
            ])
            ->add('AdresseRue', TextType::class, [
                'label' => "Rue",
                'required' => false,
            ])
            ->add('classe', EntityType::class, [
                'label' => "Classe",
                'class' => Classe::class,
                'choice_label' => 'slug'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
