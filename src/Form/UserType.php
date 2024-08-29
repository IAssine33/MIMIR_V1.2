<?php

namespace App\Form;

use App\Entity\Sitter;
use App\Entity\User;
use App\Entity\UserParent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password')
            // Pour hasher le mot de passe directement dans l'EntityType :
           /* ->add('password', PasswordType::class, [
                'hash_property_path' => 'password',
                'mapped' => false,
            ])*/

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Sitter' => 'ROLE_SITTER',
                    //'Admin' => 'ROLE_ADMIN',
                    'Parent' => 'ROLE_PARENT',
                    // Ajoutez d'autres rôles si nécessaire
                ],
                'multiple' => true,   // Permet la sélection multiple
                'expanded' => true,   // Affiche les choix sous forme de cases à cocher
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
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
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
