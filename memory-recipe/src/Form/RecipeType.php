<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la recette'
            ])
            ->add('steps', null, [
                'label' => 'Etapes de la recette',
                'help' => 'Pensez à numéroter les étapes.',
                'attr' => [
                    'placeholder' => 'Exemple : 1- Préchauffer le four à 180°; 2- ...'
                ]
            ] )
            ->add('informations', null, [
                'label' => 'Informations',
                'help' => 'Histoire ou souvenirs liés à cette recette.',
                'attr' => [
                    'placeholder' => 'Plat incontournable des repas de famille chez Mamie...'
                ]
            ])
            // ->add('picture')
            ->add('picture', FileType::class, [
                'label' => 'Choisir une image',

                // unmapped means that this field is not associated to any entity property
                // 'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Merci de ne choisir que des fichiers .png et .jpeg',
                    ])
                ],
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie'
            ])
            ->add('ingredient', CollectionType::class, [
                'label' => false,
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
