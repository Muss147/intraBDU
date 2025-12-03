<?php

namespace App\Form;

use App\Entity\Incidents;
use App\Entity\Parametres;
use App\Form\SolutionsType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                    'Début' => 'Début',
                    'En cours' => 'En cours',
                    'Fin' => 'Fin',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('code')
            ->add('dateDebut', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
                'format' => 'MM/dd/yyyy',  // Symfony attend ce format pour parser
                'input' => 'datetime',     // par défaut : transmet un \DateTime
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-[#2b62aa] block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('dateRemonte', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
                'format' => 'MM/dd/yyyy',  // Symfony attend ce format pour parser
                'input' => 'datetime',     // par défaut : transmet un \DateTime
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-[#2b62aa] block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('agence', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'agences')
                    ;
                },
            ])
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
                'required' => false,
                'format' => 'MM/dd/yyyy',  // Symfony attend ce format pour parser
                'input' => 'datetime',     // par défaut : transmet un \DateTime
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-[#2b62aa] block w-full ps-10 p-2.5',
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
                'required' => false,
                'format' => 'MM/dd/yyyy',  // Symfony attend ce format pour parser
                'input' => 'datetime',     // par défaut : transmet un \DateTime
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-[#2b62aa] block w-full ps-10 p-2.5',
                    'placeholder' => 'Sélectionner une date',
                    'id' => 'default-datepicker',
                    'datepicker' => true, // pour Flowbite
                    'datepicker-autohide' => true
                ]
            ])
            ->add('dateCompta', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false,
                'format' => 'MM/dd/yyyy',  // Symfony attend ce format pour parser
                'input' => 'datetime',     // par défaut : transmet un \DateTime
                'html5' => false, // on désactive le datepicker natif HTML5
                'attr' => [
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:ring-0 focus:border-[#2b62aa] block w-full ps-10 p-2.5',
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
                        ->setParameter('type', 'Sous catégories')
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
                        ->setParameter('type', 'Processus')
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
                        ->setParameter('type', 'Sous processus')
                    ;
                },
            ])
            ->add('pieceJointe', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/msword', // .doc
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                            'application/vnd.ms-excel', // .xls
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                        ],
                        'mimeTypesMessage' => 'Veuillez charger un document valide (PDF, Word, Excel).',
                    ])
                ],
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
