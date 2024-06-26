<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("_username", TextType::class, [
                "label" => "Pseudo",
                "attr" => [
                    "placeholder" => "Entre ton pseudo"
                ]
            ])
            ->add("_password", PasswordType::class, [
                "label" => "Mot de passe",
                "attr" => [
                    "placeholder" => "Entre ton mot de passe"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            "csrf_field_name" => "_csrf_token",
            "csrf_token_id" => "authenticate"
        ]);
    }
}
