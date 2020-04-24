<?php

namespace App\Form;

use App\Entity\Semestre;
use App\Entity\Evaluation;
use App\Entity\Enseignement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Libelle', TextType::class, [
                'required' => true,
                'label' => 'Libellé',
            ])
            ->add('DateEval', DateType::class, [
                'widget' => 'choice',
                'format' => 'dd MM yyyy',
                'required' => true,
                'label' => 'Date'
            ])
            ->add('Coef', NumberType::class, [
                'required' => false,
                'label' => 'Coefficient',
            ])
            ->add('semestre', EntityType::class, [
                'class' => Semestre::class,
                'choice_label' => 'libelle',
                'required' => true,
                'label' => 'Semestre',
            ])
            ->add('notation', NumberType::class, [
                'required'   => false,
                'label' => 'Notation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
