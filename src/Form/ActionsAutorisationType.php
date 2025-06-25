<?php

namespace App\Form;

use App\Entity\Actions;
use App\Entity\Autorisations;
use App\Entity\ActionsAutorisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ActionsAutorisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('action', EntityType::class, [
            //     'class' => Actions::class,
            //     'choice_label' => 'libelle',
            //     'label' => false,
            // ])
            ->add('checked', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'false_values' => ['0'], // interprète bien "0" comme false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActionsAutorisation::class,
        ]);
    }
}
