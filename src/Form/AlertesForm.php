<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Alertes;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Corruption, conflit d\'intérêts, fraude dans le cadre des activités de la BDU' => 'Corruption, conflit d\'intérêts, fraude dans le cadre des activités de la BDU',
                    'Manquements à l\'éthique dans le cadre des activités de la BDU' => 'Manquements à l\'éthique dans le cadre des activités de la BDU',
                    'Atteintes graves à l\'environnement résultant de l\'activité de la BDU' => 'Atteintes graves à l\'environnement résultant de l\'activité de la BDU',
                    'Atteintes graves aux droits humains et libertés fondamentales résultant de l\'activité de la BDU' => 'Atteintes graves aux droits humains et libertés fondamentales résultant de l\'activité de la BDU',
                    'Atteintes graves à la santé et à la sécurité des personnes résultant de l\'activité de la BDU' => 'Atteintes graves à la santé et à la sécurité des personnes résultant de l\'activité de la BDU',
                    'Harcèlement, sexisme, violence au travail dans le cadre des activités de la BDU' => 'Harcèlement, sexisme, violence au travail dans le cadre des activités de la BDU',
                    'Autres violations des lois et règlements dans le cadre des activités de la BDU' => 'Autres violations des lois et règlements dans le cadre des activités de la BDU'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ])
            ->add('prenoms')
            ->add('nom')
            ->add('telephone')
            ->add('email')
            ->add('adresse')
            ->add('accord', CheckboxType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('description')
            ->add('salarie', ChoiceType::class, [
                'choices' => [
                    'Oui' => 'oui',
                    'Non' => 'non',
                ],
                'expanded' => true, // Pour afficher sous forme de radio buttons
                'multiple' => false, // Une seule réponse possible
                'label' => false,
                'required' => true,
            ])
            ->add('fichiers', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '2M',
                                'mimeTypes' => [
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'image/jpeg',
                                    'image/gif',
                                ],
                                'mimeTypesMessage' => 'Seuls les fichiers PDF, Word, Excel, PowerPoint, JPEG ou GIF sont autorisés.',
                            ])
                        ]
                    ])
                ],
                'attr' => ['multiple' => true],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alertes::class,
        ]);
    }
}
