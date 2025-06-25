<?php

namespace App\Form;

use App\Entity\Actualites;
use App\Entity\Files;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualitesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('cover', EntityType::class, [
                'class' => Files::class,
                'choice_label' => 'id',
            ])
            ->add('createdUser', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('updatedUser', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('deletedUser', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actualites::class,
        ]);
    }
}
