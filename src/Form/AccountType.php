<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Sitter;
use App\Entity\UserParent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6,
                        'max' => 20,
                    ])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'sitter' => 'sitter',
                    'admin' => 'admin',
                    'parent' => 'parent'
                ]
            ])
        /*
                ->add('created_at', null, [
                    'widget' => 'single_text',
                ])

                ->add('updated_at', null, [
                    'widget' => 'single_text',
                ])

            ->add('sitter', EntityType::class, [
                'class' => Sitter::class,
                'choice_label' => 'id',
            ])
            ->add('userParent', EntityType::class, [
                'class' => UserParent::class,
                'choice_label' => 'id',
            ])
        */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
