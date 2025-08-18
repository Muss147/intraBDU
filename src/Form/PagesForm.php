<?php

namespace App\Form;

use App\Entity\Pages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('emplacement', ChoiceType::class, [
                'choices' => [
                    'Ma prise en main' => 'Ma prise en main',
                    'Tools' => 'Tools',
                    'Le guide du banquier' => 'Le guide du banquier'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ])
            ->add('contenu', TextType::class, [
                'label' => false,
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En ligne' => true,
                    'Hors ligne' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
        ]);
    }
}
