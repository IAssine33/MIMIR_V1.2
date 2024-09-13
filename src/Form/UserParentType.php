<?php

namespace App\Form;


use App\Entity\City;
use App\Entity\User;
use App\Entity\UserParent;
use App\Form\DataTransformer\CityTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserParentType extends AbstractType
{
    private $cityTransformer;

    // la methode consructeur est appeler toujours en premier
    // en appellant n'imprte quelle methode dans le meme controller
    public function __construct(CityTransformer $cityTransformer)
    {
        $this->cityTransformer = $cityTransformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'choices'=> ['Mme' => 'Mme',
                    'M' => 'M']
            ])
            ->add('phone')
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
            ])

            // Ajout des champs zip_code et name
            ->add($builder->create('city', FormType::class, [
                'label' => 'Ville',
            ])
                ->add('zip_code', TextType::class, [
                    'label' => 'Code postal',
                ])
                ->add('name', TextType::class, [
                    'label' => 'Nom de la ville',
                ])
                ->addModelTransformer($this->cityTransformer) // Application du DataTransformer
            )
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
        ->add('submit', SubmitType::class, [
            'label' => 'Sauvegarder',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserParent::class,
        ]);
    }
}
