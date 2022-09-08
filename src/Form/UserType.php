<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

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
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un email",
                    ]),
                    new Email([
                        'message' => "L'adresse email {{ value }} n'est pas valide",
                    ]),
                    new Regex([
                        'pattern' => "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
                        'match' => true,
                        'message' => "Adresse email non valide",
                    ])
                ]
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
                'required' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passe ne sont pas identique",
                'mapped' => false,
                'required' => false,
                'first_options' => [
                    'label' => "Mot de passe",
                    'constraints' => [
                        new Regex([
                        'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/i",
                        'match' => true,
                        'message' => "Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial",
                        ])
                    ]
                ],
                'second_options' => [
                    'label' => "Confirmation du mot de passe"
                ],
                
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom *",
                'attr' => [
                    'placeholder' => "Ex: john",
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un prénom",
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 40,
                        'minMessage' => "Le prénom doit contenir au minimum {{ limit }} caractères",
                        'maxMessage' => "Le prénom doit contenir au maximum {{ limit }} caractères",
                    ]),
                    new Regex([
                        'pattern' => "/^[a-z-\s]{3,40}$/i",
                        'match' => true,
                        'message' => "Le prénom ne peut contenir que des lettres, des tirets et des espaces",
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom *",
                'attr' => [
                    'placeholder' => "Ex: doe"
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un nom",
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 90,
                        'minMessage' => "Le nom doit contenir au minimum {{ limit }} caractères",
                        'maxMessage' => "Le nom doit contenir au maximum {{ limit }} caractères",
                    ]),
                    new Regex([
                        'pattern' => "/^[a-z-\s]{3,90}$/i",
                        'match' => true,
                        'message' => "Le nom ne peut contenir que des lettres, des tirets et des espaces",
                    ])
                ]
            ])
            ->add('picturesFile', FileType::class, [
                'label' => "Photo de profil",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "5000000",
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/JPG',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => "Le fichier doit être de type {{ types }}",
                        'maxSizeMessage' => "La taille maximale du fichier doit être de {{ limit }} Mo",
                    ])
                ]
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
