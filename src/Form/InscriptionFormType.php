<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod("POST");
        $builder
            ->add('email', EmailType::class, ["attr" => ["placeholder" => "Veuillez mettre un email"], "constraints" => [new Assert\Email(["message" => "Champs invalide"]), new Assert\NotBlank(["message" => "Champs invalide"])]])
            ->add('pseudo', TextType::class, ["attr" => ["placeholder" => "Veuillez mettre un pseudo"], "constraints" => [new Assert\NotBlank(["message" => "Le champs pseudo ne peut pas être vide"]), new Assert\Length(["min" => 3, "minMessage" => "Votre pseudo doit contenir au minimum 3 charactères", "max" => 50, "maxMessage" => "Votre pseudo doit contenir au maximum 50 charactères"])]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs mot de passe doivent correspondre.',
                'options' => [
                    'attr' => ['class' => 'password-field'],
                    'constraints' => [
                        new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au moins 6 caractères.',
                        ]),
                    ],
                ],
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                ],
                'second_options' => [
                    'label' => 'Confirmation de mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                ],
            ])
            ->add("submit", SubmitType::class, ["attr" => ['class' => "button"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
