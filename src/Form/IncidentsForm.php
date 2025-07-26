<?php

namespace App\Form;

use App\Entity\Incidents;
use App\Entity\IncidentsParams;
use App\Entity\Parametres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidentsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etape')
            ->add('code')
            ->add('dateDebut')
            ->add('dateRemonte')
            ->add('agence')
            ->add('region')
            ->add('rapporteur')
            ->add('fonction')
            ->add('telephone')
            ->add('email')
            ->add('description')
            ->add('montantEstime')
            ->add('datePerte')
            ->add('montantObtenu')
            ->add('dateRecup')
            ->add('dateCompta')
            ->add('natureRecup')
            ->add('montantNet')
            ->add('consequences')
            ->add('solutions')
            ->add('direction', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'id',
            ])
            ->add('directionImpacte', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'id',
            ])
            ->add('categorie', EntityType::class, [
                'class' => IncidentsParams::class,
                'choice_label' => 'id',
            ])
            ->add('sousCategorie', EntityType::class, [
                'class' => IncidentsParams::class,
                'choice_label' => 'id',
            ])
            ->add('processus', EntityType::class, [
                'class' => IncidentsParams::class,
                'choice_label' => 'id',
            ])
            ->add('sousProcessus', EntityType::class, [
                'class' => IncidentsParams::class,
                'choice_label' => 'id',
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
