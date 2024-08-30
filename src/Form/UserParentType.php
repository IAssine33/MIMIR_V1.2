<?php

namespace App\Form;


use App\Entity\City;
use App\Entity\User;
use App\Entity\UserParent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserParentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility')
            ->add('phone')
            ->add('adress')
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder',
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
            ])
        /*
                ->add('created_at', null, [
                    'widget' => 'single_text',
                ])

                ->add('updated_at', null, [
                    'widget' => 'single_text',
                ])


            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserParent::class,
        ]);
    }
}
