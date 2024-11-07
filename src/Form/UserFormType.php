<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', FileType::class, ["mapped"=>false, "attr" => ["placeholder" => "Ajoutez une image pour votre profile"]])
            ->add('pseudo', TextType::class, ["attr" => ["placeholder" => "Ajouter votre nouveau pseudo ici"], "constraints" => [new Assert\NotBlank(["message" => "Champs invalide"]), new Length([
                'min' => 6,
                'minMessage' => 'Le pseudo doit contenir au moins 6 caractères.',
                'max' => 50,
                'maxMessage' => 'Le pseudo doit contenir au maximum 50 caractères.'
            ])]])
            ->add('emploi', TextType::class, ["attr" => ["placeholder" => "Ajouter votre nouvel emploi ici"], "constraints" => [new Assert\NotBlank(["message" => "Champs invalide"]), new Length([
                'min' => 6,
                'minMessage' => 'Le champs emploi doit contenir au moins 6 caractères.',
                'max' => 50,
                'maxMessage' => 'Le champs emploi doit contenir au maximum 50 caractères.'
            ])]])
            ->add('description', TextareaType::class, ["attr" => ["placeholder" => "Ajouter une nouvelle description ici"], "constraints" => [new Length([
                'max' => 500,
                'maxMessage' => 'Le champs description doit contenir au maximum 500 caractères.'
            ])]])
            ->add("submit", SubmitType::class, ["attr" => ['class' => "button"]]);;

        $builder->get('avatar')->setRequired(false);
        $builder->get('emploi')->setRequired(false);
        $builder->get('description')->setRequired(false);
    }
}
