<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Roles;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['form_type'] === 'add') {
            // Formulaire d'ajout de nouvel utilisateur
            $builder
                ->add('nomComplet', TextType::class, [
                    'label' => false,  // Cette ligne supprime le label
                ])
                ->add('email', EmailType::class, [
                    'label' => false,
                ])
                ->add('telephone', TextType::class, [
                    'label' => false,
                ])
                ->add('role', EntityType::class, [
                    'class' => Roles::class,
                    'choice_label' => 'libelle',
                    'expanded' => true,
                    'multiple' => false,
                    'choice_attr' => function (Roles $role) {
                        return ['data-description' => $role->getDescription()];
                    },
                ])
                ->add('avatar', FileType::class, [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '2M', // Optionnel si vous souhaitez définir la taille ici aussi
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG)',
                        ])
                    ],
                ])
            ;
        } elseif ($options['form_type'] === 'edit') {
            // Formulaire de modification d'un utilisateur
            $builder
                ->add('nomComplet', TextType::class, [
                    'label' => false,
                ])
                ->add('telephone', TextType::class, [
                    'label' => false,
                ])
                ->add('activated', CheckboxType::class, [
                    'label' => false, // Le label affiché dans le formulaire
                    // 'data' => false,
                    // 'mapped' => false,
                ])
                ->add('role', EntityType::class, [
                    'class' => Roles::class,
                    'choice_label' => 'libelle',
                    'expanded' => true,
                    'multiple' => false,
                    'choice_attr' => function (Roles $role) {
                        return [
                            'data-libelle' => $role->getLibelle(),
                            'data-description' => $role->getDescription()
                        ];
                    },
                ])
                ->add('avatar', FileType::class, [
                    'label' => false,
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '2M', // Optionnel si vous souhaitez définir la taille ici aussi
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG)',
                        ])
                    ],
                ])
            ;
        } elseif ($options['form_type'] === 'change_email') {
            // Formulaire de modification d'adresse email
            $builder
                ->add('email', EmailType::class, [
                    'label' => false,
                    'required' => true,
                    'empty_data' => '',
                ])
                ->add('currentPassword', PasswordType::class, [
                    'label' => false,
                    'mapped' => false, // Ne correspond pas à une propriété de l'entité User
                    'required' => true,
                    'empty_data' => '',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir votre mot de passe actuel',
                        ]),
                    ],
                    'attr' => [
                        'placeholder' => 'Mot de passe actuel',
                        'class' => 'form-control form-control-lg form-control-solid'
                    ]
                ])
            ;
        } elseif ($options['form_type'] === 'reset_password') {
            // Formulaire de modification de mot de passe
            $builder
                ->add('currentPassword', PasswordType::class, [
                    'label' => false,
                    'mapped' => false, // Ne correspond pas à une propriété de l'entité User
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir votre mot de passe actuel',
                        ]),
                    ],
                    'attr' => [
                        'placeholder' => 'Mot de passe actuel',
                        'class' => 'form-control form-control-lg form-control-solid'
                    ]
                ])
                ->add('password', PasswordType::class, [
                    'label' => false,
                    'required' => true,
                    'empty_data' => '',
                    'attr' => [
                        'placeholder' => 'Nouveau mot de passe',
                        'class' => 'form-control form-control-lg form-control-solid'
                    ]
                ])
                ->add('confirmPassword', PasswordType::class, [
                    'mapped' => false,
                    'label' => false,
                    'attr' => ['placeholder' => 'Confirmer le mot de passe'],
                ])
            ;
        } //elseif ($options['form_type'] === 'deactivate_account') {
        //    // Formulaire de désactivation de compte
        //     $builder
        //         ->add('activated', CheckboxType::class, [
        //             'label' => false, // Le label affiché dans le formulaire
        //             'required' => true, // Rendre le champ facultatif
        //             'mapped' => false,
        //         ])
        //     ;
        // }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'form_type' => 'add', // 'add' par défaut, peut être surchargé
        ]);
    }
}
