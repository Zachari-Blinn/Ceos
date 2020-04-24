<?php

namespace App\Form;

use App\Entity\Appreciation;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Commentaire', TextareaType::class, [
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
            'data_class' => Appreciation::class,
        ]);
    }
}
