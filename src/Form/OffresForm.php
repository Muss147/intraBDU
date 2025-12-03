<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Parametres;
use App\Entity\OffresEmploi;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OffresForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('experience', ChoiceType::class, [
                'choices' => [
                    'Sans expérience' => 'Sans expérience',
                    'Moins d’un an' => 'Moins d’un an',
                    '1 à 2 ans' => '1 à 2 ans',
                    '3 à 5 ans' => '3 à 5 ans',
                    '6 à 10 ans' => '6 à 10 ans',
                    'Plus de 10 ans' => 'Plus de 10 ans',
                ],
                'placeholder' => 'Sélectionner une civilité',
                'required' => true,
                'attr' => [
                    'class' => 'form-select form-select-solid',
                ],
            ])
            ->add('niveauPoste', ChoiceType::class, [
                'choices' => [
                    'Stagiaire / Etudiant' => 'Stagiaire / Etudiant',
                    'Jeune diplômé' => 'Jeune diplômé',
                    'Débutant / Junior' => 'Débutant / Junior',
                    'Confirmé / Expérimenté' => 'Confirmé / Expérimenté',
                    'Responsable d\'équipe' => 'Responsable d\'équipe',
                    'Manager / Responsable département' => 'Manager / Responsable département',
                    'Cadre dirigeant' => 'Cadre dirigeant',
                ],
                'placeholder' => 'Sélectionner une civilité',
                'required' => true,
                'attr' => [
                    'class' => 'form-select form-select-solid',
                ],
            ])
            ->add('niveauFormation', ChoiceType::class, [
                'choices' => [
                    'Niveau secondaire' => 'Niveau secondaire',
                    'Niveau terminal' => 'Niveau terminal',
                    'Formation Professionnelle' => 'Formation Professionnelle',
                    'Baccalauréat' => 'Baccalauréat',
                    'Universitaire sans diplôme' => 'Universitaire sans diplôme',
                    'TS Bac +2' => 'TS Bac +2',
                    'Licence (LMD), Bac + 3' => 'Licence (LMD), Bac + 3',
                    'Master 1, Licence  Bac + 4' => 'Master 1, Licence  Bac + 4',
                    'Master 2, Ingéniorat, Bac + 5' => 'Master 2, Ingéniorat, Bac + 5',
                    'Magistère Bac + 7' => 'Magistère Bac + 7',
                    'Doctorat' => 'Doctorat',
                    'Certification' => 'Certification',
                    'Non diplômante' => 'Non diplômante',
                ],
                'placeholder' => 'Sélectionner une civilité',
                'required' => true,
                'attr' => [
                    'class' => 'form-select form-select-solid',
                ],
            ])
            ->add('dateExpiration')
            ->add('postes')
            ->add('description')
            ->add('missions', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('profils', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
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
            ->add('metier', EntityType::class, [
                'class' => Parametres::class,
                'choice_label' => 'libelle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.type = :type')
                        ->andWhere('p.deletedAt IS NULL')
                        ->setParameter('type', 'metiers')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffresEmploi::class,
        ]);
    }
}
