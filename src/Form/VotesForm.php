<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Agents;
use App\Entity\Votes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VotesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('agent', EntityType::class, [
                'class' => Agents::class,
                'choice_label' => fn ($agent) => $agent->getPrenoms().' '.$agent->getNom()
            ])
            ->add('votant', TextType::class, [
                'mapped' => false,
                'label' => false
            ])
            ->add('notes', CollectionType::class, [
                'entry_type' => VoteNoteForm::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Votes::class,
        ]);
    }
}
