<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\Permissions;
use App\Entity\Autorisations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AutorisationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('permission', EntityType::class, [
            //     'class' => Permissions::class,
            //     'choice_label' => 'libelle',
            //     'label' => false,
            // ])
            ->add('actions', CollectionType::class, [
                'entry_type' => ActionsAutorisationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Autorisations::class,
        ]);
    }
}
