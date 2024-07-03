<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ]
            ])
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Éditeur' => 'ROLE_EDITOR',
            //         'Administrateur' => 'ROLE_ADMIN'
            //     ],
                // 'expanded' => true,
                // 'multiple' => true,
                // 'label' => 'Rôle',
                // 'attr' => [
                //     'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 space-x-2 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                // ]
            // ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ]
            ])
            ->add('nomUtilisateur', TextType::class, [
                'label' => "Nom :",
                'required' => false,
                'empty_data' => "",
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ]
            ])
            ->add('prenomUtilisateur', TextType::class, [
                'label' => "Prénom :",
                'attr' => [
                    'class' => 'w-full rounded-md border border-gray-300 bg-white py-2 px-4 text-base font-medium text-black outline-none focus:border-yellow-500 focus:shadow-md'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

