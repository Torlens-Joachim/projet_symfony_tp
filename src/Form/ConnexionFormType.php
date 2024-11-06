<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ConnexionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod("POST")->setAction("/login");
        $builder
            ->add('email', EmailType::class, ["attr" => ["placeholder" => "Veuillez mettre un email"], "constraints" => [new Assert\Email(["message" => "Champs invalide"]), new Assert\NotBlank(["message" => "Champs invalide"])]])
            ->add('password', PasswordType::class, ["attr" => ["placeholder" => "Veuillez mettre un mot de passe"], "constraints" => [new Assert\NotBlank(["message" => "Le champs mot de passe ne peut pas être vide"]), new Assert\Length(["min" => 6, "minMessage" => "Votre mot de passe doit contenir au minimum 3 charactères"])]])
            ->add("submit", SubmitType::class, ["attr" => ['class' => "button"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
