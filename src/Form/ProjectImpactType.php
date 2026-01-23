<?php

namespace App\Form;

use App\Entity\Agents;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProjectImpact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectImpactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du projet',
                'required' => true,
                'empty_data' => '',
            ])
            ->add('owner', EntityType::class, [
                'class' => Agents::class,
                'choice_label' => fn (Agents $agent) => $agent->getNom().' '.$agent->getPrenoms(),
                'placeholder' => 'Sélectionner un agent',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.deletedAt IS NULL')
                        ->orderBy('a.nom', 'ASC');
                },
                'choice_attr' => function (Agents $agent) {
                    return [
                        'data-kt-select2-user' => $agent->getPhoto() ? $agent->getPhoto()->getFilename() 
                            : '/assets/media/svg/avatars/blank.svg',
                    ];
                },
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description / Impact',
                'attr' => ['rows' => 6],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectImpact::class,
        ]);
    }
}