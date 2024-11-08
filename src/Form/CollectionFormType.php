<?php

namespace App\Form;

use App\Entity\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

class CollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ["attr" => ["placeholder" => "Ajouter le titre de votre collection"], "constraints" => [new Assert\NotBlank(["message" => "Champs invalide"]), new Length([
                'min' => 3,
                'minMessage' => 'Le pseudo doit contenir au moins 3 caractères.',
                'max' => 100,
                'maxMessage' => 'Le pseudo doit contenir au maximum 100 caractères.'
            ])]])
            ->add('description', TextareaType::class, ["attr" => ["placeholder" => "Ajouter une description à votre collection"], "constraints" => [new Length([
                'max' => 500,
                'maxMessage' => 'Le champs description doit contenir au maximum 500 caractères.'
            ])]])
            ->add('isPublic', CheckboxType::class, [
                'label'    => 'Rendre La collection public ?',
                'attr'     => ['class' => 'form-checkbox rounded'],
                'label_attr' => ['class' => 'ml-2'],
                'help'     => 'Cochez pour que votre collection soit visible publiquement.', // 'help' sert de texte d’aide sous la case
            ])
            ->add('cover', FileType::class, ["mapped" => false, "attr" => ["placeholder" => "Ajoutez une image pour votre collection"]])
            ->add('tags', TextType::class, ["attr" => ["placeholder" => "Ajoutez des tags séparés par des virgules ( , )"]])
            ->add('categorie', TextType::class, ["attr" => ["placeholder" => "Ajouter une catégorie à votre collection"], "constraints" => new Length([
                'max' => 50,
                'maxMessage' => 'La catégorie doit contenir au maximum 50 caractères.'
            ])])

            ->add("submit", SubmitType::class, ["attr" => ['class' => "button"]]);

        $builder->get('description')->setRequired(false);
        $builder->get('isPublic')->setRequired(false);
        $builder->get('cover')->setRequired(false);
        $builder->get('categorie')->setRequired(false);
        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray): string {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString): array {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collection::class,
        ]);
    }
}
