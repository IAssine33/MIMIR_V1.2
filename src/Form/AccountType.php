<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Sitter;
use App\Entity\UserParent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password')
            ->add('roles')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
        /*
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])
        */
            ->add('sitter', EntityType::class, [
                'class' => Sitter::class,
                'choice_label' => 'id',
            ])
            ->add('userParent', EntityType::class, [
                'class' => UserParent::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
