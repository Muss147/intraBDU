<?php

namespace App\Form;

use App\Entity\Incidents;
use App\Entity\Parametres;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class IncidentsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etape', ChoiceType::class, [
                'label' => "Étape de suivi de l'incident",
                'choices' => [
                    'Début' => 'debut',
                    'En cours' => 'encours',
                    'Fin' => 'fin',
                ],
                'expanded' => true,  // Radio buttons
                'multiple' => false, // Choix unique
                'attr' => ['class' => 'grid md:grid-cols-3 gap-2'], // pour wrapper
                'choice_attr' => function($choice, $key, $value) {
                    return [
                        'class' => 'peer hidden',
                    ];
                },
                'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-gray-900'],
            ])
            ->add('code')
            ->add('dateDebut', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-primary block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('dateRemonte', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-primary block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('agence')
            ->add('region')
            ->add('rapporteur')
            ->add('fonction')
            ->add('telephone')
            ->add('email')
            ->add('description')
            ->add('montantEstime')
            ->add('datePerte', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-primary block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('montantObtenu')
            ->add('dateRecup', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-primary block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('dateCompta', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-primary block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('natureRecup')
            ->add('montantNet')
            ->add('consequences')
            ->add('solutions', CollectionType::class, [
                'entry_type' => SolutionsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'attr' => [
                    'data-controller' => 'solutions',
                ],
            ])
            ->add('direction', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'directions')
                    ;
                },
            ])
            ->add('directionImpacte', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'directions')
                    ;
                },
            ])
            ->add('categorie', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'categories')
                    ;
                },
            ])
            ->add('sousCategorie', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'sousCategories')
                    ;
                },
            ])
            ->add('processus', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'processus')
                    ;
                },
            ])
            ->add('sousProcessus', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'sousProcessus')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Incidents::class,
        ]);
    }
}
