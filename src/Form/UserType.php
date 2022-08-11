<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email *",
                'attr' => [
                    'placeholder' => "Ex: john@doe.fr",
                ],
                'required' => true
            ])
            ->add('roles', ChoiceType::class, [
                'label' => "Rang *",
                'attr' => [
                    'multiple' => false,
                ],
                'choices' => [
                    'Employer' => "ROLE_USER",
                    'Manager' => "ROLE_MANAGER",
                ],
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne sont pas identique",
                'required' => true,
                'first_options' => [
                    'label' => "Mot de passe *"
                ],
                'second_options' => [
                    'label' => "Confirmation du mot de passe *"
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom *",
                'attr' => [
                    'placeholder' => "Ex: john",
                ],
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom *",
                'attr' => [
                    'placeholder' => "Ex: doe"
                ],
                'required' => true
            ])
        ;

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
