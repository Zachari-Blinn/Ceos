<?php

namespace App\Form;

use App\Entity\Noter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class NoterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Note', NumberType::class, [
                'label' => false,
                'required' => false,
            ])
        ;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $data = $form->getData();
        $user = $data->getEleve()->getUser();
        $view->vars['label'] = $user->getNom() . ' ' . $user->getPrenom();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Noter::class,
        ]);
    }
}
