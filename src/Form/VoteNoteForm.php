<?php

namespace App\Form;

use App\Entity\Parametres;
use App\Entity\VoteNote;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class VoteNoteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Le critère est injecté côté contrôleur, on le rend invisible
            ->add('critere', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'attr' => ['hidden' => true],
                'label' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'criteres')
                    ;
                },
            ])
            ->add('note', RangeType::class, [
                'label' => false,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                    'class' => 'range-input w-full h-4 bg-transparent',
                    // la classe JS sera utilisée pour afficher dynamiquement le nombre d’étoiles
                    'oninput' => "document.getElementById(this.dataset.target).textContent = this.value + ' pts'",
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VoteNote::class,
        ]);
    }
}
