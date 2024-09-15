<?php

namespace App\Form;

use App\Entity\Sitter;
use App\Entity\User;
use App\Entity\UserParent;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Prenom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe:'],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
            ])
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
                'multiple' => false,   // Permet la sélection multiple
                'required' => true,
                'label' => 'Choisissez un role',
                'placeholder' => '--Selectionnez--',
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
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder',
            ]);
                    // j'ajoute un transformateur pour convertir une chaîne en tableau
    $builder->get('roles')->addModelTransformer(new CallbackTransformer(
        function ($rolesArray) {
            // Transformer le tableau en chaîne
            return count($rolesArray) > 0 ? $rolesArray[0] : null;
        },
        function ($roleString) {
            // Transformer la chaîne en tableau
            return [$roleString];
        }
    ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
