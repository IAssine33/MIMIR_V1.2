<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\City;
use App\Entity\Sitter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SitterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('borned_at', null, [
                'widget' => 'single_text',
            ])
            ->add('experience_years')
            ->add('civility')
            ->add('bio')
            ->add('availability')
            ->add('languages')
            ->add('certifications')
            ->add('photo_url')
            ->add('contact_info')
        /*
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])
        */
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sitter::class,
        ]);
    }
}
