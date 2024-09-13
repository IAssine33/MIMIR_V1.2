<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Sitter;
use App\Form\DataTransformer\CityTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SitterType extends AbstractType
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
            ->add('borned_at', null, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
            ])
            ->add('experience_years')
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'choices'=> ['Mme' => 'Mme',
                            'M' => 'M']
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
            ])
            ->add('availability', TextType::class, [
                'label' => 'Disponible a partir de',
            ])
            ->add('languages')
            ->add('certifications')
            ->add('photo_url', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('contact_info')

        /*
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])
*/
            /*->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])*/

        // Ajout des champs zip_code et name
        ->add($builder->create('city', FormType::class)
            ->add('zip_code', TextType::class, [
                'label' => 'Code postal',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de la ville',
            ])
            ->addModelTransformer($this->cityTransformer) // Application du DataTransformer
        )
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
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
