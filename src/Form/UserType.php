<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Eleve;
use App\Form\ProfType;
use App\Form\EleveType;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserProfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class, [
                'required' => true,
            ])
            ->add('Prenom', TextType::class, [
                'required' => true,
            ])
            ->add('Email', EmailType::class, [
                'required' => false,
                'help' => "Champ optionnel, il permet l'envoi de notification configurable dans vos préférences",
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répété mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au minimum {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Eleve' => "ROLE_ELEVE",
            //         'Professeur' => "ROLE_PROF",
            //         'Administrateur' => "ROLE_ADMIN"
            //     ],
            //     'expanded' => true,
            //     'multiple' => true,
            //     'required' => true,
            //     'mapped' => true,
            //     'label' => 'Rôles'
            // ]);
        ;
        if($options['userType'] == "prof")
        {
            $builder->add('Prof', ProfType::class, [
                'label' => false
            ]);
        }
        elseif($options['userType'] == "eleve")
        {
            $builder->add('Prof', EleveType::class, [
                'label' => false
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'userType' => null,
        ]);
    }
}
