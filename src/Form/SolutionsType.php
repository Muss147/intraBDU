<?php

// src/Form/SolutionsType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SolutionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('action', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'border-0 bg-none bg-transparent h-full w-full px-6 py-3 focus:outline-none focus:ring-0']
            ])
            ->add('responsable', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'border-0 bg-none bg-transparent h-full w-full px-6 py-3 focus:outline-none focus:ring-0']
            ])
            ->add('delai', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['class' => 'border-0 bg-none bg-transparent h-full w-full px-6 py-3 focus:outline-none focus:ring-0']
            ]);
    }
}